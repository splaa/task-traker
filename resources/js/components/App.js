import React, {useEffect} from 'react';
import ReactDOM from 'react-dom';
import TodoList from "./Todo/TodoList";
import Modal from "./modal/Modal";
import './App.css';

import Loader from "./loader/Loader";


const AddTodo = React.lazy(() => new Promise(resolve => {
        setTimeout(() => {
            resolve(import('./Todo/add-todo/AddTodo'))
        }, 3000)
    })
)


function App() {
    const [todos, setTodos] = React.useState([]);
    const [loading, setLoading] = React.useState(true);

    useEffect(() => {
        fetch('http://task-traker.herokuapp.com/api/tasks/filter/id')
            .then(response => response.json())
            .then(todos => {
                setTimeout(() => {
                    setTodos(todos);
                    setLoading(false);
                }, 2000)
            })
    }, [])

    function toggleTodo(id) {
        // console.log('### todo id: ', id);
        setTodos(todos.map(todo => {
            if (todo.id === id) {
                todo.completed = !todo.completed;
            }
            return todo;
        }))
    }

    function removeTodo(id) {
        setTodos(todos.filter(todo => todo.id !== id))
    }

    function addTodo(title) {
        setTodos(todos.concat([{
            title,
            id: Date.now(),
            completed: false
        }]))
    }

    return (
        <div className="container wrapper">
            <div className="row justify-content-center">
                <div className="col-md-8">
                    <div className="card">
                        <div className="card-header">
                            <h1>Task Tracker</h1>
                            <Modal/>
                        </div>

                        <div className="card-body">
                            <React.Suspense fallback={<p>Loading...</p>}>
                                <AddTodo onCreate={addTodo}/>
                            </React.Suspense>
                        </div>
                        <div className="card-body">

                            {loading && <Loader/>}
                            {todos.length ?
                                <TodoList todos={todos} onToggle={toggleTodo} removeTodo={removeTodo}/>
                                : (loading ? null : <p>Всё задачи выполнены!!!</p>)
                            }
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default App;

if (document.getElementById('task-tracker')) {
    ReactDOM.render(<App/>, document.getElementById('task-tracker'));
}
