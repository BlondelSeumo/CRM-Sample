<template>
	<div class="mb-primary">
		<div class="card deal-details card-with-shadow py-primary border-0">
			<template v-if="dataLoaded">
				<div class="row mb-primary">
					<div class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
						<div
							class="border-right first-border custom px-primary h-100 media pr-xl-5 pl-xl-0 user-header-media"
						>
							<div class="media d-flex align-items-center mb-3">
                <template>
                  <app-avatar
                    :title="dealPerson.contextable ? dealPerson.contextable.name : ''"
                    class="mr-2"
                    avatar-class="avatars-w-90"
                    :img=" dealPerson.contextable ? dealPerson.contextable.profile_picture ?
									urlGenerator(dealPerson.contextable.profile_picture.path): urlGenerator(`/images/${leadType}.png`) : ''"
                  />
                </template>

								<div class="media-body ml-3">
                  <span v-if="dealPerson.contextable">
                    {{ dealPerson.contextable.name }}
                  </span>
                  <p class="text-muted font-size-90 mb-2" v-else>
                    {{ $t("no_lead_added") }}
                  </p>
                  <template v-if="dealPerson.lead_type == 2">
                    <p class="text-muted font-size-90 mb-2"
                       v-for="contactPerson in dealPerson.contact_person">
                      {{ contactPerson.name }}
                    </p>

                    <p class="text-muted font-size-90 mb-2" v-if="!dealPerson.contact_person.length">
                      {{ $t("no_contact_person_added") }}
                    </p>
                  </template>
                  <template v-else>
                    <p class="text-muted font-size-90 mb-2"></p>
                  </template>

									<template v-if="dealStatus">
										<button
											class="btn btn-sm btn-success mr-1"
											@click.prevent="dealWon(dealPerson.id)"
										>
											{{ $t("won") }}
										</button>

										<button
											class="btn btn-sm btn-danger"
											@click.prevent="dealLostModal(dealPerson)"
										>
											{{ $t("lost") }}
										</button>
									</template>
									<template v-else>
										<button
											class="btn btn-sm btn-primary"
											@click.prevent="reopenDealModal(dealPerson.id)"
										>
											{{ $t("reopen") }}
										</button>
									</template>
								</div>
							</div>
						</div>
					</div>

					<div class="col-12 col-sm-12 col-md-6 col-lg-7 col-xl-7">
						<div
							class="px-primary px-sm-5 px-md-5 px-lg-0 px-xl-0 mt-5 mt-sm-5 mt-md-0 mt-lg-0 mt-xl-0 user-details"
						>
							<div class="row">
								<div
									class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 mb-xl-0 mb-lg-4 mb-md-4 mb-sm-4"
								>
									<div class="border-right second-border custom">
										<div class="media mb-primary">
											<div class="align-self-center mr-3">
												<app-icon
													class="primary-text-color"
													stroke-width="1"
													:name="'dollar-sign'"
												/>
											</div>
											<div class="media-body font-size-90">
												<p class="text-muted mb-0">{{ $t("value") }}:</p>
												<p class="mb-0">
													{{ numberWithCurrencySymbol(dealPerson.value) }}
												</p>
											</div>
										</div>
										<div class="media">
											<div class="align-self-center mr-3">
												<app-icon
													class="primary-text-color"
													stroke-width="1"
													:name="'disc'"
												/>
											</div>
											<div class="media-body font-size-90">
												<p class="text-muted mb-0">{{ $t("age") }}:</p>
												<p class="mb-0">{{ dealAge }}</p>
											</div>
										</div>
									</div>
								</div>

								<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
									<div class="media mb-primary">
										<div class="align-self-center mr-3">
											<app-icon
												class="primary-text-color"
												stroke-width="1"
												:name="'user'"
											/>
										</div>
										<div class="media-body font-size-90">
											<p class="text-muted mb-0">{{ $t("owner") }}:</p>
											<p class="mb-0" v-if="dealPerson.owner">
												{{ dealPerson.owner.full_name }}
											</p>
										</div>
									</div>
									<div class="media mb-0">
										<div class="align-self-center mr-3">
											<app-icon
												class="primary-text-color"
												stroke-width="1"
												:name="'calendar'"
											/>
										</div>
										<div class="media-body font-size-90">
											<p class="text-muted mb-0">{{ $t("created") }}:</p>
											<p class="mb-0">
												{{ formatDateToLocal(dealPerson.created_at) }}
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="px-primary">
					<app-step-input-selector
						class="mb-3"
						:step-lists="stageListAsPipelineId"
						:step-complete="stageIndexAsId"
						:show-label="true"
						v-model="formData.stage_id"
						@stepChanges="updateDealFromStepInput"
						:disabled="!dealStatus"
					/>
					<div
						class="d-flex flex-wrap align-items-center justify-content-between"
					>
						<div class="font-size-90 text-muted d-flex align-items-center">
							<template v-if="dealPerson.pipeline">
								<app-icon
									class="mr-2"
									stroke-width="1"
									width="16"
									height="16"
									:name="'trello'"
								/>
								{{ dealPerson.pipeline.name }}
							</template>
							<template v-if="dealPerson.stage">
								<app-icon
									class
									stroke-width="1"
									width="12"
									height="12"
									:name="'chevron-right'"
								/>
								{{ stageListAsPipelineId[stageIndexAsId].name }}
							</template>
						</div>
						<div
							class="font-size-90 text-muted d-flex align-items-center"
							data-toggle="tooltip"
							title="Expecting closing date">
							<app-icon
								class="mr-2"
								stroke-width="1"
								width="16"
								height="16"
								name="calendar"
							/>
							{{
								dealPerson.expired_at == null
									? $t("not_added_yet")
									: formatDateToLocal(dealPerson.expired_at)
							}}
						</div>
					</div>
				</div>

				<app-confirmation-modal
					v-if="isModalActive"
					modal-id="deal-reopen-modal"
					:message="$t('are_you_sure_want_to_reopen_this_deal')"
					@confirmed="confirmed"
					@cancelled="cancelled"
				/>

				<app-deal-lost-confirm-modal
					v-if="isDealOwnConfirmModal"
					modal-id="lost-deal-confirm-modal"
					:dealLostReasonData="dealLost"
					@close-modal="closeModal"
				/>

				<app-deal-won-confirm-modal
					v-if="isWonModal"
					modal-id="won-deal-confirm-modal"
					:dealWonData="dealPerson"
					@won-close-modal="wonCloseModal"
				/>
			</template>
			<app-overlay-loader v-else/>
		</div>
	</div>
</template>

<script>
import {
	formatDateToLocal,
	numberWithCurrencySymbol,
	getCurrencySymbol,
	urlGenerator
} from "../../../../Helpers/helpers";
import {FormMixin} from "../../../../../core/mixins/form/FormMixin";
import {api} from "../../../../Helpers/api";
import {collect} from "../../../../Helpers/Collection";

export default {
	name: "DealsContactPerson",
	props: ["dealPerson", 'leadType'],
	mixins: [FormMixin],
	data() {
		return {
			formatDateToLocal,
			numberWithCurrencySymbol,
			getCurrencySymbol,
			urlGenerator,
			stageList: [
				{id: "day_1", name: "1 day"},
				{id: "day_1", name: "2 days"},
				{id: "day_1", name: "3 days"},
				{id: "day_1", name: "4 days"},
				{id: "day_1", name: "5 days"},
				{id: "day_1", name: "6 days"},
			],
			pipelineList: [],
			dataLoaded: false,
			isModalActive: false,
			isDealOwnConfirmModal: false,
			isWonModal: false,
			wonData: null,
			rowData: null,
			dealLost: {},
			formData: {},
			statusIdForDone: null,
			currentStage: null,
			histories: null,
		};
	},
	computed: {
		stageListAsPipelineId() {
			let stageList = this.stageList;

			let timeLine = [];

			if (this.histories.length != 0) {
				this.stageList.forEach((v, i) => {
					let arr = [];
					//current stage
					if (v.id == this.currentStage) {
						this.histories[i].timestamps.push(moment());
					}

					// new stage which has no record on history column
					if (!this.histories.find(el => el.stage_id == v.id)) {
						let item = {stage_id: v.id, timestamps: []};
						this.histories.splice(i, 0, item);
					}
					arr.push(this.histories[i].timestamps);
					timeLine.push(arr);
				});
			} else {
				stageList.forEach((v, i) => {
					let arr = [];
					if (v.id == this.currentStage) {
						arr.push([this.dealPerson.created_at, moment()]);
					} else if (v.id < this.currentStage) {
						arr.push([moment(), moment()]);
					}
					timeLine.push(arr);
				});
			}
			stageList.forEach((v, i) => {
				if (timeLine[i] == undefined) console.log(i);
				if (v.id) {
					let duration = 0;
					duration =
						timeLine[i].length == 0 || timeLine[i][0].length == 0
							? null
							: timeLine[i][0].reduce((acc, curr, index) => {
								//except last index
								if (index < timeLine[i][0].length - 1) {
									let prev = moment(timeLine[i][0][index - 1]),
										current = moment(timeLine[i][0][index]),
										next = moment(timeLine[i][0][index + 1]);

									let d = next.diff(current, "seconds");
									if (current.diff(prev, "seconds") == 0 && index > 0) {
										// console.log(current.diff(prev, "seconds"), i);
										d = 0;
									}
									return acc + d;
								}
								return acc;
							}, 0);
					let secondsInOneday = 86400,
						secondsInOneHour = 3600,
						secondsInOneMin = 60;
					// console.log(duration);
					if (duration != null) {
						v.label =
							duration <= secondsInOneMin
								? "-"
								: moment.duration(duration, "seconds").humanize();
					}
				}
			});
			return stageList;
		},
		stageIndexAsId() {
			let index = undefined;
			let stageListByPipeLine = this.stageList.filter(
				(v) => v.pipeline_id == this.dealPerson.pipeline_id
			);
			for (let i = 0; i < stageListByPipeLine.length; i++) {
				if (stageListByPipeLine[i].id === this.currentStage) {
					index = i;
				}
			}

			return index;
		},
		dealStatus() {
			return (
				this.dealPerson.status.name == "status_open" &&
				this.dealPerson.status.type == "deal"
			);
		},
		dealAge() {
			let createdAt = moment(this.dealPerson.created_at).local().format("YYYY-MM-DD HH:mm:ss");
			let updatedAt = moment(this.dealPerson.updated_at).local().format("YYYY-MM-DD HH:mm:ss");

			if (
				this.dealPerson.status.name == "status_open" &&
				this.dealPerson.status.type == "deal") {
				let duration = moment().diff(createdAt, "seconds");
				return moment.duration(duration, "seconds").humanize();
			} else {
				let duration = moment(updatedAt).diff(moment(createdAt), "seconds");
				return moment.duration(duration, "seconds").humanize();
			}
		},
	},
	methods: {
		getPipeline() {
			this.axiosGet(route('pipelines.index'))
				.then((response) => {
					this.pipelineList = this.collection(response.data.data).shaper(
						"name"
					);
				})
				.catch(({error}) => {
				});
		},
		getStages() {
			this.axiosGet(route('stages.index',{_query:{all:true}}))
				.then((response) => {
					this.formData.pipeline_id = this.dealPerson.pipeline_id;
					this.stageList = collect(response.data).where('pipeline_id', '=', this.formData.pipeline_id).sortBy('priority').get();
					this.dataLoaded = true;
				})
				.catch(({error}) => {
				});
		},
		reopenDealModal(id) {
			this.isModalActive = true;
			this.rowData = id;
			$("#deal-reopen-modal").modal("show");
		},
		confirmed() {
			this.formData.title = this.dealPerson.title;
			this.formData.pipeline_id = this.dealPerson.pipeline_id;
			this.formData.stage_id = this.dealPerson.stage_id;

            this.axiosGet(
                route(`statuses.index`, {_query: { name: "status_open", type: "deal"}}))
				.then((res) => {
					this.formData.status_id = collect(res.data).first().id;
					this.axiosPatch({
						url: route('deals.update', {id: this.dealPerson.id}),
						data: this.formData,
					}).then((response) => {
						this.$emit("update-request");
						this.$toastr.s(response.data.message);
					});
				});
		},
		cancelled() {
			this.isModalActive = false;
			this.rowData = null;
			$("#deal-reopen-modal").modal("hide");
		},
		dealLostModal(dealPerson) {
			this.isDealOwnConfirmModal = true;
			this.dealLost = dealPerson;
			$("#lost-deal-confirm-modal").modal("show");
		},
		dealWon(id) {
			this.isWonModal = true;
			this.wonData = id;
			$("#won-deal-confirm-modal").modal("show");
		},
		wonCloseModal() {
			this.isWonModal = false;
			this.wonData = null;
			$("#won-deal-confirm-modal").modal("hide");
		},
		closeModal() {
			this.isDealOwnConfirmModal = false;
			this.dealLost = {};
			$("#lost-deal-confirm-modal").modal("hide");
		},
		updateDealFromStepInput(index) {
			this.formData.title = this.dealPerson.title;
			this.formData.stage_id = this.stageListAsPipelineId[index].id;
			this.axiosPatch({
				url: "/deals/" + this.dealPerson.id,
				data: this.formData,
			})
				.then((res) => {
					this.currentStage = this.formData.stage_id;
					//get new histories of this deal
					this.axiosGet("/deals/" + this.dealPerson.id)
						.then((response) => {
							this.histories = response.data.histories;
						})
						.catch((er) => console.log(er));
				})
				.catch((er) => console.log(er));
		},
	},
	mounted() {
		this.getPipeline();
		this.getStages();
		this.currentStage = this.dealPerson.stage_id;
		this.histories = this.dealPerson.histories;
	},
};
</script>
