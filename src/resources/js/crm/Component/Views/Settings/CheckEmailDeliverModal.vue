<template>
	<app-modal modal-id="check-email-modal" modal-size="large" modal-alignment="top" @close-modal="closeModal">
		<template slot="header">
			<h5 class="modal-title">{{ headerTitle }}</h5>
			<button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close">
				<span><app-icon :name="'x'"></app-icon></span>
			</button>
		</template>

		<template slot="body">
			<app-note :title="$t('no_email_settings_found')" :notes="note" content-type="html" padding-class="p-3"/>
		</template>
		<template slot="footer">
			<button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" @click.prevent="closeModal">
				{{ $t('close') }}
			</button>
		</template>
	</app-modal>
</template>


<script>

import {urlGenerator} from "@app/Helpers/helpers";

export default {
	name: "CheckEmailDeliverModal",
	data() {
		return {
			note: `${this.$t('no_delivery_settings_warning')}
					<a href="${route('settings.page', {_query: { tab: "Email%20setup"}})}">${this.$t('here')}</a> ${this.$t('to_add_email_setting')}`,
			urlGenerator,
		}
	},
	props: {
		headerTitle: {
			type: String,
			required: true
		}
	},
	methods: {
		closeModal(value) {
			this.$emit("close-modal", value);
		},
	}
}
</script>
