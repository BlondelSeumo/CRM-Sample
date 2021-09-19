<template>
	<div class="card border-0 card-with-shadow">
		<div class="card-header bg-transparent p-primary d-flex justify-content-between align-items-center">
			<h5 v-show="isDetailsEditable" class="card-title text-muted mb-0">{{ $t('address_details') }}</h5>
			<h5 v-show="!isDetailsEditable" class="card-title text-muted mb-0">{{ $t('edit_address_details') }}</h5>

			<div>
				<a href="#" v-show="!isDetailsEditable" class="text-muted" @click.prevent="showDetailsClose">
					<app-icon stroke-width="1" name="x-square"/>
				</a>
				<a href="#" v-show="!isDetailsEditable" class="text-muted" @click.prevent="showDetailsSave">
					<app-icon stroke-width="1" name="check-square"/>
				</a>
				<a href="#" v-show="isDetailsEditable" class="text-muted" @click.prevent="showDetailsEditable">
					<app-icon stroke-width="1" name="edit"/>
				</a>
			</div>
		</div>

		<div class="card-body">
			<div v-show="isDetailsEditable">
        <template v-if="(addressDetails.country || addressDetails.area || addressDetails.state || addressDetails.city || addressDetails.zip_code || addressDetails.address)">

          <div class="form-group mb-3">
            <div class="form-row" v-if="addressDetails.country">
              <label class="mb-0 text-muted col-3 d-flex align-items-center">{{ $t('country') }}</label>
              <div class="col-9">
                {{ addressDetails.country.name }}
              </div>
            </div>
          </div>

          <div class="form-group mb-3">
            <div class="form-row" v-if="addressDetails.area">
              <label class="mb-0 text-muted col-3 d-flex align-items-center">{{ $t('area') }}</label>
              <div class="col-9">
                {{ addressDetails.area }}
              </div>
            </div>
          </div>

          <div class="form-group mb-3">
            <div class="form-row" v-if="addressDetails.city">
              <label class="mb-0 text-muted col-3 d-flex align-items-center">{{ $t('city') }}</label>
              <div class="col-9">
                {{ addressDetails.city }}
              </div>
            </div>
          </div>

          <div class="form-group mb-3">
            <div class="form-row" v-if="addressDetails.state">
              <label class="mb-0 text-muted col-3 d-flex align-items-center">{{ $t('state') }}</label>
              <div class="col-9">
                {{ addressDetails.state }}
              </div>
            </div>
          </div>

          <div class="form-group mb-3">
            <div class="form-row" v-if="addressDetails.zip_code">
              <label class="mb-0 text-muted col-3 d-flex align-items-center">{{ $t('zip_code') }}</label>
              <div class="col-9">
                {{ addressDetails.zip_code }}
              </div>
            </div>
          </div>

          <div class="form-group mb-3">
            <div class="form-row" v-if="addressDetails.address">
              <label class="mb-0 text-muted col-3 d-flex align-items-center">{{ $t('address') }}</label>
              <div class="col-9">
                {{ addressDetails.address }}
              </div>
            </div>
          </div>
        </template>
        <template v-else>
          <p class="text-muted">{{ $t('not_added_yet') }}</p>
          <a href="#" class="font-size-90" @click.prevent="showDetailsEditable">
            {{ $t('add_address') }}
          </a>
        </template>
			</div>

			<div v-show="!isDetailsEditable">

				<div class="form-group form-row mb-3">
					<label class="col-3 mb-0 d-flex align-items-center">{{ $t('country') }}</label>
					<div class="col-9">
						<app-input
							type="search-select"
							list-value-field="name"
							:list="countryList"
							:placeholder="$t('choose_a_country')"
							v-model="addressDetails.country_id"
						/>
					</div>
				</div>

				<div class="form-group form-row mb-3">
					<label class="col-3 mb-0 d-flex align-items-center">{{ $t('area') }}</label>
					<div class="col-9">
						<app-input type="text"
                       :placeholder="$t('enter_area')"
						           v-model="addressDetails.area"/>
					</div>
				</div>


				<div class="form-group form-row mb-3">
					<label class="col-3 mb-0 d-flex align-items-center">{{ $t('city') }}</label>
					<div class="col-9">
						<app-input type="text"
                       :placeholder="$t('enter_city')"
						           v-model="addressDetails.city"/>
					</div>
				</div>

				<div class="form-group form-row mb-3">
					<label class="col-3 mb-0 d-flex align-items-center">{{ $t('state') }}</label>
					<div class="col-9">
						<app-input type="text"
                       :placeholder="$t('enter_state')"
						           v-model="addressDetails.state"/>
					</div>
				</div>

				<div class="form-group form-row mb-3">
					<label class="col-3 mb-0 d-flex align-items-center">{{ $t('zip_code') }}</label>
					<div class="col-9">
						<app-input type="text"
                       :placeholder="$t('enter_zip_code')"
						           v-model="addressDetails.zip_code"/>
					</div>
				</div>

				<div class="form-group form-row mb-3">
					<label class="col-3 mb-0 d-flex align-items-center">{{ $t('address') }}</label>
					<div class="col-9">
						<app-input type="textarea"
                       :placeholder="$t('add_address_details_here')"
						           v-model="addressDetails.address"/>
					</div>
				</div>

			</div>

		</div>
	</div>
</template>

<script>
import {FormMixin} from "../../../core/mixins/form/FormMixin";

export default {
	name: "AddressDetails",
	mixins: [FormMixin],
	props: {
		addressDetails: {
			type: Object,
			required: true
		},
		addressUpdateUrl: {
			type: String,
			required: true
		}
	},

	data() {
		return {
			isDetailsEditable: true,
			countryList: [],
      formData: {},
		}
	},

	methods: {
		showDetailsSave() {
      this.formData.name = this.addressDetails.name;
      this.formData.contact_type_id = this.addressDetails.contact_type_id;
      this.formData.owner_id = this.addressDetails.owner_id;
      this.formData.country_id = this.addressDetails.country_id;
      this.formData.area = this.addressDetails.area;
      this.formData.city = this.addressDetails.city;
      this.formData.state = this.addressDetails.state;
      this.formData.zip_code = this.addressDetails.zip_code;
      this.formData.address = this.addressDetails.address;
			this.submitFromFixin('patch', this.addressUpdateUrl, this.formData)
		},
		afterSuccess(response) {
			this.$toastr.s(response.data.message);
			this.isDetailsEditable = true
			this.$emit('update-request');
		},
		showDetailsClose() {
			this.isDetailsEditable = true;
		},
		showDetailsEditable() {
			this.isDetailsEditable = false
		},

		getAllCountrys() {
			this.axiosGet(route('countries')).then(({data}) => {
				this.countryList = data;
			});
		}
	},

	created() {
		this.getAllCountrys();
	}
}
</script>
