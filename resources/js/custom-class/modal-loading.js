// MODAL LOADING
class ModalLoading
{
	constructor(obj)
	{
		this.object = obj;
	}

	setLoading()
	{
		$(`${ this.object } .modal-body`).prepend(`
			<h2 class="text-muted text-center lighter --custom-modal-loading my-4">
				<i class="fas fa-spinner fa-spin"></i>
				Loading please wait...
			</h2>	
		`);

		$(`${ this.object } .--custom-modal-loading`).nextAll().hide();
	}

	removeLoading()
	{
		$(`${ this.object } .--custom-modal-loading`).nextAll().show();
		$(`${ this.object } .--custom-modal-loading`).remove();
	}
}