<template>
  <div>
    <app-overlay-loader v-if="loaded" />
    <div class="row" v-else>
      <div class="col-12 col-sm-12 col-md-12 col-lg-11 col-xl-11">
        <draggable tag="div"
                   v-model="socialLinks"
                   @start="isDragging = true"
                   @end="isDragging = false">
          <transition-group type="transition" name="flip-list">
            <div
                class="d-flex flex-wrap flex-column flex-lg-row align-items-lg-center justify-content-between py-half-primary"
                v-for="(socialLink, index) in socialLinks"
                :key="'social-link'+index">
              <div class="d-flex align-items-center">
                <app-icon name="menu" class="mr-3 cursor-grab"/>
                <div class="avatars-w-40 mr-3">
                  <div class="no-img rounded-circle shadow">
                      <span :key="socialLink.icon">
                          <app-icon :name="socialLink.icon" width="19px" height="19"/>
                      </span>
                  </div>
                </div>
                <p class="mb-0">{{ socialLink.name.replace('_', ' ') }}</p>
              </div>
              <div class="mt-2 mt-lg-0">
                <div v-if="socialLink.link && !socialLink.edit"
                     :key="'with-link-'+index"
                     class="d-flex align-items-center text-muted" style="max-width: 310px;">
                  <p class="mb-0 mr-2 text-truncate">{{ socialLink.link }}</p>
                  <a href="#" class="text-muted" @click.prevent="removeSocialLink(index)">
                    <app-icon name="trash"/>
                  </a>
                </div>
                <div v-else :key="'without-link-'+index">
                  <div v-if="socialLink.edit"
                       :key="'without-link-edit-'+index"
                       class="d-flex align-items-center">
                    <app-input :id="'social-link-'+index"
                               class="mr-2"
                               type="text"
                               v-model="socialLink.link"/>
                    <button type="submit" class="btn btn-primary mr-2"
                            @click.prevent="addSocialLink(index)">{{ $t('add') }}
                    </button>
                    <a href="#" class="text-muted" @click.prevent="editToggle(index)">
                      <app-icon name="x"/>
                    </a>
                  </div>
                  <div v-else
                       :key="'without-link-show-'+index"
                       class="text-lg-right">
                    <button type="submit" class="btn btn-primary mr-2"
                            @click.prevent="editToggle(index)">
                      {{ $t('link') }}
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </transition-group>
        </draggable>
      </div>
    </div>
    <div v-else></div>
  </div>

</template>

<script>

import draggable from 'vuedraggable';
import {FormMixin} from "@core/mixins/form/FormMixin";

export default {
  name: "SocialLinks",
  components: {draggable},
  mixins: [FormMixin],
  data() {
    return {
      loaded: false,
      socialLinks: [
        {
          "id": 1,
          "name": "Facebook",
          "icon": "facebook",
          "link": null,
          "edit": false
        },
        {
          "id": 2,
          "name": "Twitter",
          "icon": "twitter",
          "link": null,
          "edit": false
        },
        {
          "id": 3,
          "name": "Linkedin",
          "icon": "linkedin",
          "link": null,
          "edit": false
        },
        {
          "id": 4,
          "name": "Behance",
          "icon": "behance",
          "link": null,
          "edit": false
        },
        {
          "id": 5,
          "name": "Instagram",
          "icon": "instagram",
          "link": null,
          "edit": false
        },
        {
          "id": 6,
          "name": "YouTube",
          "icon": "youtube",
          "link": null,
          "edit": false
        },
        {
          "id": 7,
          "name": "Dribble",
          "icon": "dribble",
          "link": null,
          "edit": false
        },
        {
          "id": 8,
          "name": "Google Plus",
          "icon": "google-plus",
          "link": null,
          "edit": false
        },
        {
          "id": 9,
          "name": "Skype",
          "icon": "skype",
          "link": null,
          "edit": false
        },
        {
          "id": 10,
          "name": "Pinterest",
          "icon": "pinterest",
          "link": null,
          "edit": false
        }
      ],
    }
  },

  methods: {
    removeSocialLink(index) {
      this.socialLinks[index].link = null;
      this.update(this.socialLinks[index]);
    },
    addSocialLink(index) {
      this.socialLinks[index].edit = false;
      this.update(this.socialLinks[index]);
    },
    editToggle(index) {
      this.socialLinks[index].edit = !this.socialLinks[index].edit;
      setTimeout(()=>{
        $('#social-link-'+index).focus();
      });
    },
    update(data) {
      this.loaded = true;
      this.axiosPatch({
        url: route('user_social_link.update', {userId: window.user.id}),
        data: {'user_id': window.user.id, [data.name.replace(' ', '_')]: data.link}
      }).then((response) => {
        this.$toastr.s(response.data.message);
        this.$hub.$emit('update-social-link');
      }).finally(() => {
        this.loaded = false;
      })
    },
    getUserSocialLin() {
      this.loaded = true;
      this.axiosGet(route('user_social_link.index')).then((response) => {
        if (response.data) {
          this.socialLinks = response.data;
        } else {
          this.socialLinks
        }
      }).finally(() => {
        this.loaded = false;
      })
    }
  },
  mounted() {
    this.getUserSocialLin();
  }
}
</script>
