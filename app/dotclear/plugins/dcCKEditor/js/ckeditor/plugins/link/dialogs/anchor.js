﻿CKEDITOR.dialog.add('anchor',function(editor){var loadElements=function(element){this._.selectedElement=element;var attributeValue=element.data('cke-saved-name');this.setValueOf('info','txtName',attributeValue||'');};function createFakeAnchor(editor,attributes){return editor.createFakeElement(editor.document.createElement('a',{attributes:attributes}),'cke_anchor','anchor');}
function getSelectedAnchor(selection){var range=selection.getRanges()[0],element=selection.getSelectedElement();range.shrink(CKEDITOR.SHRINK_ELEMENT);element=range.getEnclosedNode();if(element&&element.type===CKEDITOR.NODE_ELEMENT&&(element.data('cke-real-element-type')==='anchor'||element.is('a'))){return element;}}
return{title:editor.lang.link.anchor.title,minWidth:300,minHeight:60,onOk:function(){var name=CKEDITOR.tools.trim(this.getValueOf('info','txtName'));var attributes={id:name,name:name,'data-cke-saved-name':name};if(this._.selectedElement){if(this._.selectedElement.data('cke-realelement')){var newFake=createFakeAnchor(editor,attributes);newFake.replace(this._.selectedElement);if(CKEDITOR.env.ie){editor.getSelection().selectElement(newFake);}}else{this._.selectedElement.setAttributes(attributes);}}else{var sel=editor.getSelection(),range=sel&&sel.getRanges()[0];if(range.collapsed){var anchor=createFakeAnchor(editor,attributes);range.insertNode(anchor);}else{if(CKEDITOR.env.ie&&CKEDITOR.env.version<9)
attributes['class']='cke_anchor';var style=new CKEDITOR.style({element:'a',attributes:attributes});style.type=CKEDITOR.STYLE_INLINE;style.applyToRange(range);}}},onHide:function(){delete this._.selectedElement;},onShow:function(){var sel=editor.getSelection(),fullySelected=getSelectedAnchor(sel),fakeSelected=fullySelected&&fullySelected.data('cke-realelement'),linkElement=fakeSelected?CKEDITOR.plugins.link.tryRestoreFakeAnchor(editor,fullySelected):CKEDITOR.plugins.link.getSelectedLink(editor);if(linkElement){loadElements.call(this,linkElement);!fakeSelected&&sel.selectElement(linkElement);if(fullySelected){this._.selectedElement=fullySelected;}}
this.getContentElement('info','txtName').focus();},contents:[{id:'info',label:editor.lang.link.anchor.title,accessKey:'I',elements:[{type:'text',id:'txtName',label:editor.lang.link.anchor.name,required:true,validate:function(){if(!this.getValue()){alert(editor.lang.link.anchor.errorName);return false;}
return true;}}]}]};});