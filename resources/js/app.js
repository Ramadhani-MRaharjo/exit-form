import './bootstrap';
import '../css/exit-form-responsive.css';
import '../css/exit-form-print.css';

const sidebar = document.querySelector('[data-sidebar]');
document.querySelector('[data-sidebar-open]')?.addEventListener('click', () => sidebar?.classList.add('is-open'));
document.querySelector('[data-sidebar-close]')?.addEventListener('click', () => sidebar?.classList.remove('is-open'));

const modal = document.querySelector('[data-form-modal]');
const toggleModal = (open) => {
	modal?.classList.toggle('open', open);
};
document.querySelector('[data-open-form]')?.addEventListener('click', () => toggleModal(true));
document.querySelectorAll('[data-close-form]').forEach((button) => button.addEventListener('click', () => toggleModal(false)));
modal?.addEventListener('click', (event) => { if (event.target === modal) toggleModal(false); });
document.querySelector('[data-form-search]')?.addEventListener('input', (event) => {
	const query = event.target.value.toLowerCase();
	document.querySelectorAll('[data-form-list] tr').forEach((row) => { row.hidden = !row.textContent.toLowerCase().includes(query); });
});

const requirementMenu = document.querySelector('.requirement-sidebar');
document.querySelector('[data-req-menu]')?.addEventListener('click', () => requirementMenu?.classList.toggle('open'));
document.querySelector('[data-req-menu-close]')?.addEventListener('click', () => requirementMenu?.classList.remove('open'));
document.querySelector('[data-employee-form]')?.addEventListener('click', () => {
	const form = document.querySelector('.employee-form');
	form?.scrollIntoView({ behavior: 'smooth', block: 'start' });
	form?.querySelector('[name="employee_id"]')?.focus({ preventScroll: true });
});
document.querySelector('[data-req-search]')?.addEventListener('input', (event) => {
	const query = event.target.value.toLowerCase();
	document.querySelectorAll('[data-req-list] tr').forEach((row) => { row.hidden = !row.textContent.toLowerCase().includes(query); });
});

const resetFilters = () => {
	document.querySelectorAll('.requirement-filters input, .requirement-filters select').forEach((field) => {
		if (field.tagName === 'SELECT') {
			field.selectedIndex = 0;
			return;
		}
		field.value = '';
	});
	document.querySelectorAll('[data-req-list] tr').forEach((row) => {
		row.hidden = false;
	});
};

document.querySelectorAll('.requirement-filters .requirement-secondary').forEach((button) => {
	button.addEventListener('click', (event) => {
		event.preventDefault();
		resetFilters();
	});
});

document.querySelector('[data-delete-users]')?.addEventListener('submit', (event) => {
	const selectedUsers = event.currentTarget.querySelectorAll('input[name="user_ids[]"]:checked');
	if (!selectedUsers.length) {
		event.preventDefault();
		alert('Pilih user yang ingin dihapus terlebih dahulu.');
		return;
	}

	if (!confirm('Apakah Anda yakin ingin menghapus user yang dipilih?')) {
		event.preventDefault();
	}
});

const userModal = document.querySelector('[data-user-modal-box]');
const userForm = userModal?.querySelector('[data-user-form]');
const userModalTitle = userModal?.querySelector('[data-user-modal-title]');
const userSubmit = userModal?.querySelector('[data-user-submit]');

document.querySelector('[data-user-modal]')?.addEventListener('click', () => {
	userForm?.reset();
	if (userForm) {
		userForm.action = userForm.dataset.createUrl;
		userForm.querySelector('[name="_method"]')?.remove();
	}
	if (userModalTitle) userModalTitle.textContent = 'Add New User';
	if (userSubmit) userSubmit.textContent = 'Save User';
	userModal?.classList.add('open');
});

document.querySelectorAll('[data-user-edit]').forEach((link) => {
	link.addEventListener('click', (event) => {
		event.preventDefault();
		if (!userForm) return;

		userForm.reset();
		userForm.action = link.dataset.updateUrl;
		let methodOverride = userForm.querySelector('[name="_method"]');
		if (!methodOverride) {
			methodOverride = document.createElement('input');
			methodOverride.type = 'hidden';
			methodOverride.name = '_method';
			userForm.append(methodOverride);
		}
		methodOverride.value = 'PATCH';

		for (const field of ['nik', 'name', 'department', 'email', 'type', 'allowed', 'approver_id']) {
			const value = field === 'approver_id' ? link.dataset.approverId : link.dataset[field.replace('_', '-')];
			userForm.elements.namedItem(field).value = value ?? '';
		}

		if (userModalTitle) userModalTitle.textContent = 'Update User';
		if (userSubmit) userSubmit.textContent = 'Update User';
		userModal?.classList.add('open');
	});
});

document.querySelectorAll('[data-close-user]').forEach((button) => button.addEventListener('click', () => userModal?.classList.remove('open')));
userModal?.addEventListener('click', (event) => {
	if (event.target === userModal) userModal.classList.remove('open');
});
document.addEventListener('keydown', (event) => {
	if (event.key === 'Escape') userModal?.classList.remove('open');
});

const handoverModal = document.querySelector('[data-handover-modal-box]');
document.querySelector('[data-handover-modal]')?.addEventListener('click', () => handoverModal?.classList.add('open'));
document.querySelectorAll('[data-close-handover]').forEach((button) => button.addEventListener('click', () => handoverModal?.classList.remove('open')));
handoverModal?.addEventListener('click', (event) => {
	if (event.target === handoverModal) handoverModal.classList.remove('open');
});
document.querySelectorAll('[data-delete-handover]').forEach((form) => form.addEventListener('submit', (event) => {
	if (!confirm('Hapus form draft ini?')) event.preventDefault();
}));

