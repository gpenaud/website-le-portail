/*lePortail/cke-addon/content.css*/
div.newslist {
    position:relative;
    margin: 7px auto;
    outline:1px solid #ffd25caa;
    padding-top: 23px;
    float:left;
    width:100%;
}
div.newslist.center {
    width:50%;
    margin-left:auto;
    margin-right: auto;
}

div.newslist.left {
    width:50%;
    margin-left:7px;
    float:left;
    clear:none;
}

div.newslist.right {
    width:50%;
    float:right;
    margin:7px;
    clear:none;
}

div.newslist:hover span.widgettitle {
    opacity: 1;
    border-radius: 0;
}

div.newslist span.widgettitle{
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

div.newslist label {
    margin-left:11%;
}

div.newslist input[type='checkbox'] {

}

div.newslist .label {
    margin:1% 2%;
    display: flex;
    min-height: 23px;
    justify-content: flex-start;
    align-content: center;
    align-items: center;
    margin-right: 2%;
}

div.newslist .label>*{
    flex-grow:2;
}

div.newslist .label>label{
    margin-left:2%;
    flex-grow: 0;
    display: flex;
    gap: 2px;
}

div.newslist .label>input{
    margin-left: 1%;
}

div.newslist .label > h4 {
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
}

div.newslist .label > input[type='checkbox'] {

    flex-grow: 0;
    flex-shrink: 0;
    /*! margin: auto 0; */
    padding: 0;
    /*! align-self: flex-start; */
}
