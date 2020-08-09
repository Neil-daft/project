import React, { Component } from 'react';

export default class SearchComponent extends Component {
	render() {
		if (this.props.searching) {
			return (
				<form className="form-inline my-2 my-lg-0">
					<input className="form-control mr-sm-2"/>
						<button className="btn btn-outline-success my-2 my-sm-0">Search</button>
				</form>
			);
		}
	}
}
