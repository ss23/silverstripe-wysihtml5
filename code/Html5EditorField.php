<?php

class Html5EditorField extends HtmlEditorField {
	public function __construct($name, $title = null, $rows = 30, $cols = 20, $value = '', $form = null) {
		TextAreaField::__construct($name, $title, $rows, $cols, $value, $form); // Avoid hitting the constructor for HtmlEditorField
		
		// Include Javascript as required
		Requirements::javascript("wysihtml5/javascript/advanced.js");
		Requirements::javascript("wysihtml5/javascript/wysihtml5-0.3.0.min.js");
		Requirements::css("wysihtml5/css/toolbar.css");
	}

	public function Field() {
		$id = $this->id();
		$css = Director::BaseURL() . 'themes/' . SSViewer::current_theme() . '/css/screen.css';
		
		// Add Javascript for this field
		Requirements::customScript(<<<JS
new wysihtml5.Editor("$id", {
	toolbar:	"$id-wysihtml5-editor-toolbar",
	parserRules:	wysihtml5ParserRules,
	stylesheets:	["$css"]
});
JS
);
		$value = new SS_HTMLValue($this->value);
		
		$toolbar = <<<HTML
<div class="wysihtml5-editor-toolbar" id="$id-wysihtml5-editor-toolbar" style="display: none;">
      <header>
        <ul class="commands">
          <li data-wysihtml5-command="bold" title="Make text bold (CTRL + B)" class="command" href="javascript:;" unselectable="on">Bold</li>
          <li data-wysihtml5-command="italic" title="Make text italic (CTRL + I)" class="command" href="javascript:;" unselectable="on">Italics</li>
          <li data-wysihtml5-command="insertUnorderedList" title="Insert an unordered list" class="command" href="javascript:;" unselectable="on">Bullets</li>
          <li data-wysihtml5-command="insertOrderedList" title="Insert an ordered list" class="command" href="javascript:;" unselectable="on">Numbered List</li>
          <li data-wysihtml5-command="createLink" title="Insert a link" class="command" href="javascript:;" unselectable="on">Insert Link</li>
          <li data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h5" title="Insert headline 1" class="command wysihtml5-command-active" href="javascript:;" unselectable="on">Heading 1</li>
          <li data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h6" title="Insert headline 2" class="command" href="javascript:;" unselectable="on">Heading 2</li>
          <li data-wysihtml5-action="change_view" title="Show HTML" class="action" href="javascript:;" unselectable="on"></li>
        </ul>
      </header>
      <div data-wysihtml5-dialog="createLink" style="display: none;">
        <label>
          Link:
          <input data-wysihtml5-dialog-field="href" value="http://">
        </label>
        <a data-wysihtml5-dialog-action="save">OK</a>&nbsp;<a data-wysihtml5-dialog-action="cancel">Cancel</a>
      </div>
    </div>
HTML
;
		return $toolbar . $this->createTag('textarea', array(
			'class'		=> $this->extraClass(),
			'rows'		=> $this->rows,
			'cols'		=> $this->cols,
			'style'		=> 'width: 97%; height: ' . ($this->rows * 16) . 'px', // prevents horizontal scrollbars
			'id'		=> $this->id(),
			'name'		=> $this->name,
		), htmlentities($value->getContent(), ENT_COMPAT, 'UTF-8'));
	}
}
