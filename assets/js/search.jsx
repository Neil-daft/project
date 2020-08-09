import React from 'react';
import { render } from 'react-dom';
import SearchComponent from "./Search/SearchComponent.jsx";

const isSearching = true;
render(
	<SearchComponent searching={isSearching} />,
	document.getElementById('headingReact')
);
