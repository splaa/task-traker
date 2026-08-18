import React, { Component } from "react";
import "./Modal.css";

export default class Modal extends Component {
    constructor(props) {
        super(props);
        this.state = this.getInitialState();
    }

    getInitialState() {
        return {
            title: "",
            description: "",
            status: "View",
        };
    }

    componentDidUpdate(prevProps) {
        if (this.props.task && this.props.task !== prevProps.task) {
            this.setState({
                title: this.props.task.title || "",
                description: this.props.task.description || "",
                status: this.props.task.status || "View",
            });
        }
    }

    handleChange = (event) => {
        this.setState({ [event.target.name]: event.target.value });
    };

    handleSubmit = (event) => {
        event.preventDefault();
        if (this.state.title.trim()) {
            this.props.onSave({
                id: this.props.task ? this.props.task.id : null,
                title: this.state.title,
                description: this.state.description,
                status: this.state.status,
            });
        }
    };

    render() {
        const { task, onClose } = this.props;

        if (!task) {
            return null;
        }

        return (
            <div
                className="modal"
                tabIndex="-1"
                role="dialog"
                onClick={onClose}
            >
                <div
                    className="modal-dialog"
                    role="document"
                    onClick={(e) => e.stopPropagation()}
                >
                    <div className="modal-content">
                        <form onSubmit={this.handleSubmit}>
                            <div className="modal-header">
                                <h5 className="modal-title">Edit Task</h5>
                                <button
                                    type="button"
                                    className="close"
                                    onClick={onClose}
                                    aria-label="Close"
                                >
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div className="modal-body">
                                <div className="form-group">
                                    <label htmlFor="title">Title</label>
                                    <input
                                        id="title"
                                        name="title"
                                        type="text"
                                        className="form-control"
                                        value={this.state.title}
                                        onChange={this.handleChange}
                                        required
                                        maxLength="100"
                                    />
                                </div>
                                <div className="form-group">
                                    <label htmlFor="description">
                                        Description
                                    </label>
                                    <textarea
                                        id="description"
                                        name="description"
                                        className="form-control"
                                        value={this.state.description}
                                        onChange={this.handleChange}
                                        rows="3"
                                    />
                                </div>
                                <div className="form-group">
                                    <label htmlFor="status">Status</label>
                                    <select
                                        id="status"
                                        name="status"
                                        className="form-control"
                                        value={this.state.status}
                                        onChange={this.handleChange}
                                    >
                                        <option value="View">View</option>
                                        <option value="In Progress">
                                            In Progress
                                        </option>
                                        <option value="Done">Done</option>
                                    </select>
                                </div>
                            </div>
                            <div className="modal-footer">
                                <button
                                    type="submit"
                                    className="btn btn-primary"
                                >
                                    Save changes
                                </button>
                                <button
                                    type="button"
                                    onClick={onClose}
                                    className="btn btn-secondary"
                                >
                                    Close
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        );
    }
}
