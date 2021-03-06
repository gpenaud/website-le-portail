/* -- BEGIN LICENSE BLOCK ----------------------------------
//
// This file is part of dotSlick, a plugin for Dotclear 2.
// 
// Copyright (c) 2019 Bruno Avet
// Licensed under the GPL version 2.0 license.
// A copy of this license is available in LICENSE file or at
// http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
//
// -- END LICENSE BLOCK ------------------------------------*/





.wds-title {
    color:red;
}

.wds-figure {
    overflow: auto hidden;
    width: 84%;
    text-align: center;
    padding: 3%;
    display: block;
    height: 350px;
    line-height: 350px;
}

.wds-figure>img {
    vertical-align: middle;
    display: inline-block;
    margin: auto;
}

.wds-figure>figcaption {
line-height: 1em;
position: absolute;
bottom: 17px;
background-color: #ffd25c80;
padding: 5px;
font-family: "Sans";
font-style: italic;
border-radius: 7px;

font-size: x-small;
width: 75%;
}

.wds-figure>figcaption>h5 {
padding: 0;
margin: 0;
font-size: unset;
}

.wds {
    width:90%;
    margin:auto;
}

.wds-desc {
    opacity:0.5;
    display:none;
}

.wds:hover > .dotSlickWidgetLabel {
    opacity: 1;
    border-radius: 0;
}
.dotSlickWidgetLabel {
    position: absolute;
    top:0;
    right: 5%;
    background-color: #ffd25c;
    height: 20px;
    padding: 1px 21px 1px 21px;
    padding-top: 1px;
    padding-right: 21px;
    padding-bottom: 1px;
    padding-left: 21px;
    font-family: sans;
    border-radius: 0 0 0 15px;
    opacity: 0.3;
    color: ivory;
    text-shadow: 0 0 2px red;
    transition: 1s borderRadius;
}
