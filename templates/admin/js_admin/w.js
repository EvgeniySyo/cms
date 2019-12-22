var idElemet = '';
function Visible(id)
{
document.getElementById(id).style.display = 'block';
}
function Invisble(id)
{
document.getElementById(id).style.display = 'none';
}
/* main */
function SelectMain(id,idH)
{
	document.getElementById(id).style.background = '#f0f0f0';
	document.getElementById(id).style.color = '#6b6b6b';
	
	document.getElementById(idH).style.display = 'block';
}
function SelectMainEnd(id,idH)
{
	document.getElementById(id).style.background = 'none';
	document.getElementById(id).style.color = '#ffffff';
	document.getElementById(idH).style.display = 'none';
}
function windowUser(id)
{
	document.getElementById(id).style.display = 'block';
}
function windowsUserClosed(id)
{
	document.getElementById(id).style.display = 'none';
}
function windowUser1(id)
{
	document.getElementById(id).style.display = 'block';
}
function windowsUserClosed1(id)
{
	document.getElementById(id).style.display = 'none';
}
/* end main */
function ShowPanel(id)
{
	jQuery('#'+id).show("slow");
}
function HiddenPanel(id)
{
	jQuery('#'+id).hide("slow");
}

function PanelWorks(id)
{
	if (jQuery('#'+id).is(":hidden")) {
			jQuery('#'+id).show("slow");
		} else {
			jQuery('#'+id).slideUp();
		}
}
function hiddenWindow(id)
{
	jQuery("#dialog"+id).hide("slow");
}
function ShowWindow(id)
{
	jQuery('#dialog'+id).show("slow");
}