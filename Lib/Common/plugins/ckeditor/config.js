/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	//config.uiColor = '#AADC6E';
	//config.width	= 500;
	//config.height	= 300;
	
	config.language	= 'zh-cn';
	config.skin		= 'v2';
	
	config.toolbar_admin = [
		['Source','-','Preview','-','PasteFromWord','-','Undo','Redo','-','Find','Replace'],
		['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
		'/',
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Link','Unlink','Anchor'],
		['Image','Flash','Table','HorizontalRule','SpecialChar'],
		'/',
		['Styles','Format','Font','FontSize'],
		['TextColor','BGColor'],
		['Maximize', 'ShowBlocks']
	];
	config.toolbar_users = [
		['PasteFromWord','-','Undo','Redo','-','Find','Replace','-',
		'Bold','Italic','Underline','Strike','-','-','NumberedList','BulletedList','-',
		'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','Image','-','HorizontalRule','Table'],
		'/',
		['Styles','Format','Font','FontSize','-','TextColor','BGColor','-','Maximize']
	];
	config.toolbar = 'admin';
	
	config.font_names = '宋体/宋体;黑体/黑体;Arial/Arial;Times New Roman/Times New Roman;Verdana/Verdana';
	
	config.enterMode = CKEDITOR.ENTER_DIV; //CKEDITOR.ENTER_P,CKEDITOR.ENTER_BR,CKEDITOR.ENTER_DIV
};
