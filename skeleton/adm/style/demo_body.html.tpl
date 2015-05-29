&lt;!-- INCLUDE overall_header.html -->

<h1>&#123;L_SETTINGS}</h1>

<form id="acp_board" method="post" action="&#123;U_ACTION}">
	<fieldset>
		<dl>
			<dt><label for="acme_demo_goodbye">&#123;L_ACP_DEMO_GOODBYE}</label></dt>
			<dd><input type="radio" class="radio" name="acme_demo_goodbye" value="1" &lt;!-- IF ACME_DEMO_GOODBYE -->checked="checked"&lt;!-- ENDIF -->/> &#123;L_YES} &nbsp;
				<input type="radio" class="radio" name="acme_demo_goodbye" value="0" &lt;!-- IF not ACME_DEMO_GOODBYE -->checked="checked"&lt;!-- ENDIF --> /> &#123;L_NO}</dd>
		</dl>

		<p class="submit-buttons">
			<input class="button1" type="submit" id="submit" name="submit" value="&#123;L_SUBMIT}" />&nbsp;
			<input class="button2" type="reset" id="reset" name="reset" value="&#123;L_RESET}" />
		</p>

		&#123;S_FORM_TOKEN}
	</fieldset>
</form>
&lt;!-- INCLUDE overall_footer.html -->
