import React, {Component} from 'react';

import './Loader.css';

class Loader extends Component {

    render() {
        return (
            <div className="loader-load" style=
                     {{
                         display: 'flex',
                         justifyContent: 'center',
                         margin: '.5rem'
                     }}>
                <div><h1>Loader</h1></div>
                <div className="lds-spinner">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        );
    }

}

export default Loader;




