import React from 'react';
import ReactDOM from 'react-dom';
import TodoList from "./Todo/TodoList";


import './App.css';
import AddTodo from "./Todo/add-todo/AddTodo";

function App() {
    const [todos, setTodos] = React.useState([
        {id: 1, completed: false, title: 'Купить хлеб'},
        {id: 2, completed: true, title: 'Купить масло'},
        {id: 3, completed: false, title: 'Купить молоко'},
    ]);

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
                        <div className="card-header"><h1>Task Tracker</h1></div>

                        <div className="card-body">
                            <AddTodo onCreate={addTodo}/>
                        </div>
                        <div className="card-body">
                            {todos.length ?
                                <TodoList todos={todos} onToggle={toggleTodo} removeTodo={removeTodo}/>
                                : <p>Всё  задачи выполнены!!!</p>
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
