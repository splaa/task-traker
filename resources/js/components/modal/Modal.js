import React from 'react';
import './Modal.css';


export default class Modal extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            isOpen: false
        }
    }


    render() {
        return (
            <React.Fragment>
                <button onClick={()=> this.setState({isOpen: true})}>Open Modal</button>
                {this.state.isOpen &&
                <div className="modal" tabIndex="-1" role="dialog">
                    <div className="modal-dialog" role="document">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5 className="modal-title">Modal title</h5>
                                <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div className="modal-body">
                                <p>Modal body text goes here.</p>
                            </div>
                            <div className="modal-footer">
                                <button type="button" className="btn btn-primary">Save changes</button>
                                <button
                                    onClick={()=>this.setState({isOpen: false})}
                                    type="button"
                                    className="btn btn-secondary"
                                    data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                }
            </React.Fragment>
        );
    }

};
