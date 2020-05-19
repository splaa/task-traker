import React, {Component, useContext} from 'react';
import PropTypes from 'prop-types';

import './TodoItem.css';

class TodoItem extends Component {

    render() {


        const classes = []
        const styles = {
            li: {
                display: 'flex',
                justifyContent: 'space-between',
                alignItems: 'center',
                padding: '.5rem 1rem',
                border: '1px solid #ccc',
                borderRadius: '4px',
                marginBottom: '.5rem'
            },
            input: {
                marginRight: '1rem'
            }
        }
        const {todo, index, onChange, removeTodo} = this.props;

        if (todo.completed) {
            classes.push('done')
        }

        console.log('### todo ', todo, classes);
        return (
            <li style={styles.li}>
                <span className={classes.join(' ')}>
                    <input
                        type="checkbox"
                        checked={todo.completed}
                        style={styles.input}
                        onChange={() => onChange(todo.id)}
                    />
                <strong>{index + 1})</strong> &nbsp;
                    {todo.title}
                </span>
                <button
                    className={"rm"}
                    onClick={removeTodo.bind(null,todo.id)}
                >
                    &times;
                </button>
            </li>
        );
    }

}

TodoItem.propTypes = {
    todo: PropTypes.object.isRequired,
    index: PropTypes.number,
    onChange: PropTypes.func.isRequired
}

export default TodoItem;
