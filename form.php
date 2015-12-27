<?php
require_once 'HTML/QuickForm.php';

class Form extends HTML_QuickForm {
	function
	__construct($name)
	{
		parent::__construct($name);
		$renderer =& $this->defaultRenderer();
		$renderer->setHeaderTemplate("
	<tr>
		<td class=\"form-header\" align=\"left\" valign=\"top\" colspan=\"2\"><b>{header}</b></td>
	</tr>");

		$this->setRequiredNote('<span style="font-size:80%; color:#ff0000;">*</span><span style="font-size:80%;">必須項目</span>');
	}
}
?>
