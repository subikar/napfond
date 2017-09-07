<?php
class Sparx_Tweets_Block_Adminhtml_System_Config_Implementcode extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element){
       
        return '
<div class="entry-edit-head collapseable"><a onclick="Fieldset.toggleCollapse(\'tweets_template\'); return false;" href="#" id="tweets_template-head" class="open">Implement Code</a></div>
<input id="tweets_template-state" type="hidden" value="1" name="config_state[tweets_template]">
<fieldset id="tweets_template" class="config collapseable" style="">
<h4 class="icon-head head-edit-form fieldset-legend">Code for  Twitter Tweets</h4>

<div id="messages">
    <ul class="messages">
        <li class="notice-msg">
            <ul>
                <li>'.Mage::helper('tweets')->__('Add code below to a template file').'</li>				
            </ul>
        </li>
    </ul>
</div>
<br>
<ul>
	<li>
		<code>
			$this->getLayout()->createBlock('.'"tweets/tweets"'.')->setTemplate('.'"tweets/tweets.phtml"'.')->toHtml();
		</code>
	</li>
</ul>
<br>
<div id="messages">
    <ul class="messages">
        <li class="notice-msg">
            <ul>
                <li>'.Mage::helper('tweets')->__('Put code on a CMS page').'</li>				
            </ul>
        </li>
    </ul>
</div>
<br>
<ul>
	<li>
		<code>
			{{block type="tweets/tweets" name="tweets.cms" template="tweets/tweets.phtml"}}
		</code>
	</li>
</ul>
<br>
<div id="messages">
    <ul class="messages">
        <li class="notice-msg">
            <ul>
                <li>'.Mage::helper('tweets')->__('Please copy and paste the code below on one of xml layout files where you want to show the Twitter Tweets.').'</li>				
            </ul>
        </li>
    </ul>
</div>

<ul>
	<li>
		<code>
		 &lt;block type="tweets/tweets" name="tweets.tweets" template="tweets/tweets.phtml"&gt;<br>
		
		&lt;/block&gt;
		</code>	
	</li>
</ul>

<br>

</fieldset>';
    }
}
