<template>
    <div class="card border-0 card-with-shadow mb-primary">
        <div class="card-header d-flex align-items-center justify-content-between p-primary bg-transparent">
            <h5 class="card-title text-muted m-0">{{ $t('organization_info') }}</h5>
        </div>
        <div class="card-body">
            <template v-if="Object.keys(organizationInfo.organizations).length > 0">
                <template v-for="(organization, index) in organizationInfo.organizations">
                    <div class="media d-flex align-items-center mb-3">
                        <app-avatar
                            :img="organization.profile_picture
                                ? urlGenerator(organization.profile_picture.path)
                                : organization.profile_picture"
                            :title="organization.name"
                            avatar-class="avatars-w-40"
                            class="mr-2"/>

                        <div class="media-body">
                            {{ organization.name }}
                        </div>
                    </div>
                </template>
            </template>
            <template v-else>
                {{$t('no_organization_added') }}
            </template>
        </div>
    </div>
</template>

<script>
import {urlGenerator} from "@app/Helpers/helpers";

export default {
    name: "DealOrganizationInfo",
    props: ['organizationInfo'],
    data() {
        return {
            urlGenerator,
        }
    },
}
</script>

