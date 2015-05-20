
    <div id="footer">
        <div id="txt-footer">© Education et formation <?php echo date("Y"); ?> &nbsp;- &nbsp;v<?php echo Config::POSI_VERSION; ?></div>
    </div>
	
	<script type="text/javascript" src="<?php echo SERVER_URL; ?>media/js/navigator-agent.js"></script>
	<script type="text/javascript">

		var navAgent = new NavigatorAgent();

		var footerTxt = document.getElementById('footer').innerHTML;
		footerTxt += '<p>' + navAgent.getName() + ' ' + navAgent.getVersion() + '</p>';
		document.getElementById('footer').innerHTML = footerTxt;


	</script>