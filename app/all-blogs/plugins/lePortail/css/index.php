/*lePortail/css/editor.home.css*/

div.grille {
    margin: 0;
    padding: 0;
    display:grid;
    grid-template-columns:59% 40%;
    grid-template-rows:50% 50% auto 480px;
    grid-column-gap: 1%;
    grid-row-gap: 1%;
    /*! grid-gap:1%; */
    width: 98%;
    position: relative;
    padding: 1%;
    box-sizing: border-box;
}


div.grille .presentation {
    grid-row : 1 / span 2;
    vertical-align: middle;
    margin: auto;
    background-color:#bbb4;
    border-radius:10px;
    box-shadow: 0 0 3px #0004;
    height: 100%;
    width: 100%;
}

div.grille .presentation .bloc {
    font-size:small;
    line-height:2em;
    padding: 1px 17px 1px 17px;
    height: 420px;
    width: 75%;
    margin: 0 auto;
}

div.grille .presentation .bloc h2{
    font-size:2em;
}

div.grille .presentation .bloc ul {
    padding-top: 28px;
}


div.grille #mandala, div.grille #gallery {
    grid-column: 1 / span 2;
    text-align: center;
    margin:auto;
    grid-row: 4;
    height: 480px;
}


div.grille #gallery {
    width: 100%;
    background-color:#bbb4;
    border-radius:10px;
    box-shadow: 0 0 3px #0004;
    margin: 0;
    grid-row: 3;
    /*! height: 500px; */
}

div.grille #mandala {
    width: 100%;
}

div.cke_widget_block

div.grille .wds {
    /*! width: 75vw; */
    overflow: hidden;
}

.grille div.slavelist, .grille div.newslist {
    margin:0 !important;
    padding:0;
    grid-column: 2;
    width:100% !important;
    outline:unset !important;
    background-color:#bbb4;
    height:100%;
    border-radius:10px;
    box-shadow: 0 0 3px #0004;	
    box-sizing: border-box;
}

.grille div.slavelist {
    padding-top:25px;
    overflow:hidden;
}


body{
    font-family: Tahoma, Arial, Verdana, Geneva, Helvetica, sans-serif;
    /*! line-height: 1.6em; */
    box-sizing: border-box;
    margin:1%;
    background-color:#ffb;
}

