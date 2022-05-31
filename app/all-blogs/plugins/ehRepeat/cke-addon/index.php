/*ehRepeat/cke-addon/content.css*/

div.slavelist {
    position:relative;
    margin: 7px auto;
    outline:1px solid #ffd25caa;
    padding-top:23px;
    float:left;
}

div.slavelist.center {
    width: 50%;
    margin-left:auto;
    margin-right: auto;
}

div.slavelist.left {
    width:50%;
    margin-left:7px;
    float:left;
    clear:none;
}

div.slavelist.right {
    width: 50%;
    float:right;
    margin:7px;
    clear:none;
}

div.slavelist:hover span.widgettitle {
    opacity: 1;
    border-radius: 0;
}

div.slavelist span.widgettitle{
    position: absolute;
    top:0;
    right: 0;
    background-color: #ffd25c;
    height: 20px;
    padding: 1px 21px 1px 21px;
    padding-top: 1px;
    padding-right: 21px;
    padding-bottom: 1px;
    padding-left: 21px;
    font-family: "Sans";
    border-radius: 0 0 0 15px;
    opacity: 0.7;
    color: ivory;
    text-shadow: 0 0 2px red;
    transition: 1s borderRadius;
}

div.slavelist .group {
    border:1px ridge #bbba;
    margin:3px;
    background-color: #bbb1
}

div.slavelist .label {
    margin:1% 2%;
    display: flex;
    min-height: 23px;
    justify-content: flex-start;
    align-content: center;
    align-items: center;
    margin-right: 2%;
}

div.slavelist .label>*{
    flex-grow:2;
    flex-basis: 50px;
}

div.slavelist .label>label{
    margin-left:2%;
    flex-grow: 0;
    display: flex;
    gap: 2px;
}

div.slavelist .label>label>input{
    margin:auto 0;
}

div.slavelist .label>input{
    margin-left: 1%;
    flex-basis: unset;
}

div.slavelist .label h4 {
    float: right;
    background-color: #ffb;
    border: 2px inset #bbb4;
    height: 17px;
    width: 64%;
    /*! justify-items: center; */
    flex-shrink: 0;
    flex-grow: 2;
    margin-left: 3%;
    justify-self: first baseline;
    display: block;
    overflow: hidden;
    white-space: nowrap;
    cursor:text;
    margin: 1px 3px;
}

div.slavelist .label > input[type='checkbox'] {

    flex-grow: 0;
    flex-shrink: 0;
    /*! margin: auto 0; */
    padding: 0;
    /*! align-self: flex-start; */
}

div.slavelist .label > input[type='radio'] {
    flex-grow: 0;
}


div.slavelist .label input[name="style"]:checked ~ div {
    display: flex;
}

div.slavelist .label input[name="style"]:not(checked) ~ div {
    display: none;
}

div.slavelist .label div {
    display:flex;
    flex-direction: column;
    flex-grow: 2;
}

div.slavelist .label div h4{
    float:none;
    align-self: end;
    width: 96%;
}
div.slavelist .label div p{
    font-size: xx-small;
    display: block;

    margin: 0 10px;
    text-align: justify;
    text-justify: distribute;
    font-style: italic;
}

div.slavelist .label div p em{
    font-weight:bold;
}