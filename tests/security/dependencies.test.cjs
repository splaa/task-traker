const assert = require('node:assert/strict');
const { test } = require('node:test');
const fs = require('node:fs');
const os = require('node:os');
const path = require('node:path');
const http = require('node:http');
const shellQuote = require('shell-quote');
const webpack = require('webpack');
const WebpackDevServer = require('webpack-dev-server');

test('shell-quote rejects newlines following a comment token', () => {
    // GHSA-pqg4-j6r4-53mv: a newline must not terminate the generated comment.
    assert.throws(() => shellQuote.quote([{ comment: 'safe' }, '\necho injected']));
    assert.deepEqual(shellQuote.parse(shellQuote.quote(['echo', 'hello world'])), ['echo', 'hello world']);
});

function request(port, pathname) {
    return new Promise((resolve, reject) => {
        const req = http.get({ hostname: '127.0.0.1', port, path: pathname }, res => {
            let body = '';
            res.setEncoding('utf8');
            res.on('data', chunk => { body += chunk; });
            res.on('end', () => resolve({ status: res.statusCode, body }));
        });
        req.on('error', reject);
    });
}

test('patched middleware works with Mix dev-server and blocks output traversal', { timeout: 30000 }, async () => {
    const root = fs.mkdtempSync(path.join(os.tmpdir(), 'task-traker-webpack-'));
    fs.writeFileSync(path.join(root, 'entry.js'), 'console.log("security-test-bundle");');
    const compiler = webpack({
        mode: 'development',
        entry: path.join(root, 'entry.js'),
        output: { path: path.join(root, 'dist'), filename: 'bundle.js', publicPath: '/assets' },
        infrastructureLogging: { level: 'error' },
    });
    const server = new WebpackDevServer({
        host: '127.0.0.1', port: 0, static: false, hot: true,
        devMiddleware: { stats: 'errors-only' },
    }, compiler);
    try {
        await server.start();
        await new Promise(resolve => server.middleware.waitUntilValid(resolve));
        compiler.outputFileSystem.writeFileSync(path.join(root, 'secret.txt'), 'outside-output-secret');
        const port = server.server.address().port;
        const bundle = await request(port, '/assets/bundle.js');
        assert.equal(bundle.status, 200);
        assert.ok(bundle.body.includes('security-test-bundle'));
        // GHSA-g84c-rxfj-3j2c: publicPath deliberately has no trailing slash.
        for (const pathname of ['/assets../secret.txt', '/assets%2e%2e/secret.txt']) {
            const response = await request(port, pathname);
            assert.ok([403, 404].includes(response.status), `unexpected status ${response.status}`);
            assert.ok(!response.body.includes('outside-output-secret'));
        }
    } finally {
        await server.stop();
        fs.rmSync(root, { recursive: true, force: true });
    }
});
