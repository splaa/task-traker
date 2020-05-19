import React, {Component} from 'react';
import PropTypes from 'prop-types';


class AddTodo extends Component {
    constructor(props) {
        super(props);
        this.state ={
            value: ''
        }

        this.submitHandler = this.submitHandler.bind(this);
        this.addTodo = this.addTodo.bind(this);
    }

    render() {
        const {onCreate} = this.props;

        return (
            <div>
                <form onSubmit={this.submitHandler}>
                    <input
                        type="text"
                        value={this.state.value}
                        onChange={this.addTodo}/>
                    <button type="submit">Add todo</button>
                </form>
            </div>
        );
    }

    addTodo(event) {
        this.setState({value: event.target.value})
    }

    submitHandler(event) {
        event.preventDefault();

        if (this.state.value.trim()) {
            this.props.onCreate(this.state.value);
            this.setState({value: ''});
        }
    }

}

AddTodo.propTypes = {
    onCreate: PropTypes.func.isRequired
}


export default AddTodo;
