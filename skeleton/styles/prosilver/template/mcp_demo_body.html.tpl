&lt;!-- INCLUDE mcp_header.html -->

<form method="post" id="mcp" action="&#123;U_POST_ACTION}">

	<h2>&#123;L_MCP_DEMO_TITLE}</h2>

	<div class="panel">
		<div class="inner">

			<fieldset>
				<dl>
					<dt><label for="username">&#123;L_SELECT_USER}&#123;L_COLON}</label></dt>
					<dd><input name="username" id="username" type="text" class="inputbox" /></dd>
				</dl>
			</fieldset>

		</div>
	</div>

	<fieldset class="submit-buttons">
		<input type="reset" value="&#123;L_RESET}" name="reset" class="button2" />&nbsp;
		<input type="submit" name="submituser" value="&#123;L_SUBMIT}" class="button1" />
		&#123;S_FORM_TOKEN}
	</fieldset>
</form>

&lt;!-- INCLUDE mcp_footer.html -->
