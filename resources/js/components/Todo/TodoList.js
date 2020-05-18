import React, {Component} from 'react';
import PropTypes from 'prop-types';
import './TodoList.css';
import TodoItem from "./todo-item/TodoItem";


class TodoList extends Component {


    render() {
        const {todos, onToggle, removeTodo} = this.props;
        const styles = {
            ul: {
                listStyle: 'none',
                margin: 0,
                padding: 0

            }
        }

        return (
            <div>
                <ul style={styles.ul}>
                    {todos.map((todo, index) => {
                        return (<TodoItem
                            todo={todo}
                            key={todo.id}
                            index={index}
                            removeTodo={removeTodo}
                            onChange={onToggle}
                        />)
                    })}
                </ul>
            </div>
        );
    }

}

TodoList.propTypes ={
    todos: PropTypes.arrayOf(PropTypes.object).isRequired,
    onToggle:PropTypes.func.isRequired
}


export default TodoList;

