class AddingUI
{
	constructor(obj)
	{
		this.object = obj;
	}

	removeInputAfterSaving()
	{
		$(this.object).find('.form-control').val('');
	}
}