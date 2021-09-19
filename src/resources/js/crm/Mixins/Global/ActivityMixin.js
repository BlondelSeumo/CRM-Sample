export default {
  data() {
    return {
      preLoader: true,
      isActivityModal: false,
      isNoteModal: false,
      value: '',
      activitiesList: [],
      rowData: {},
      noteRowData: null,
      isfileNoteFilter: true,
      collaborators: [],
      participants: [],
      formData: {},
      today: {dateOnly: moment().format('YYYY-MM-DD')},

      activitiesFilter: [
        {
          name: 'Todo',
        },
        {
          name: 'Done',
        },
        {
          name: 'Incomplete',
          key: 'incomplete'
        },
        {
          name: 'Files',
          key: 'file'
        },
        {
          name: 'Notes',
          key: 'note'
        }
      ],
    }
  },
    computed:{
          todoStatusId(){
              return this.activityStatus.find((status)=>{
                  if (status.name == 'status_todo'){
                      return status;
                  }
              });
          },
        doneStatusId(){
            return this.activityStatus.find((status)=>{
                if (status.name == 'status_done'){
                    return status;
                }
            });
        }
    },
  mounted() {
      this.activitiesFilter.forEach((el) => {
          if (el.name == 'Todo'){
              el["key"] = `status=${this.todoStatusId.id}`;
          }else if (el.name == 'Done'){
              el["key"] = `status=${this.doneStatusId.id}`;
          }
      });
    this.formData = this.Data;
    this.collaboratorParticipant();
    this.updateActivity();
    this.filterActivities(this.activityFilterUrl, `status=${this.todoStatusId.id}`);
  },
  methods: {
    updateActivity() {
      this.$hub.$on("activity-list", (value = true) => {
        if (value) {
          if (this.editUrl){
            this.getUpdatedData();
          }
          if (this.activityFilterUrl){
            this.filterActivities(this.activityFilterUrl, `${value}`);
          }
        }
      });
    },
    getUpdatedData() {
      this.axiosGet(this.editUrl)
        .then(({data}) => {
          if (this.quickView){
            this.formData = data.current;
          }else {
            this.formData = data;
          }
          if (this.componentType == 'deal'){
            this.$emit("update-data", this.formData);
          }
          this.collaboratorParticipant();
        })
        .catch((error) => console.log(error));
    },
    filterActivities(url, key) {
      if (this.formData){
        this.preLoader = true;
        this.value = key;
        if (key == "file") {
          this.isfileNoteFilter = false;
          this.axiosGet(`${this.fileFilterUrl}`).then((response) => {
            this.activitiesList = response.data;
          }).finally(() => {
            this.preLoader = false;
          }).catch((error) => console.log(error));
        }else if (key == "note"){
            this.isfileNoteFilter = false;
            this.axiosGet(`${this.noteFilterUrl}`).then((response) => {
                this.activitiesList = response.data;
            }).finally(() => {
                this.preLoader = false;
            }).catch((error) => console.log(error));
        } else {
          this.axiosGet(`${url}?${this.value}`)
            .then((response) => {
              this.activitiesList = response.data;
              this.isfileNoteFilter = true;
            }).finally(() => {
            this.preLoader = false;
          }).catch((error) => console.log(error));
        }
      }
    },
    activityChangeStatus(id) {
      this.preLoader = true
      this.axiosPost({
        url: route('activities.done', {id: id})
      }).then((response) => {
        this.$toastr.s(response.data.message);
        this.filterActivities(this.activityFilterUrl, `status=${this.doneStatusId.id}`);
      }).finally(() => {
        this.preLoader = false;
      }).catch((error) => console.log(error));
    },
    activityDelete(id) {
      this.axiosDelete(route('activities.destroy', {id: id})).then((response) => {
        this.$toastr.s(response.data.message);
        this.filterActivities(this.activityFilterUrl, `status=${this.todoStatusId.id}`);
      }).catch((error) => console.log(error));
    },

    editActivity(activity) {
      this.$emit("open-activity", activity);
    },

    fileDownload(activity) {
      this.axiosGet(route('activities.file-download', {id: activity.id}), {responseType: 'blob'}).then((response) => {
        var fileURL = window.URL.createObjectURL(new Blob([response.data]));
        var fileLink = document.createElement('a');
        fileLink.href = fileURL;
        fileLink.setAttribute('download', 'activity' + activity.path);
        document.body.appendChild(fileLink);
        fileLink.click();
      })
    },

    editNote(activity) {
      this.$emit("open-note-modal", activity);
    },

    noteDelete(id) {
      this.axiosDelete(route('activities.delete-note', {id: id})).then((response) => {
        this.$toastr.s(response.data.message);
        this.filterActivities(this.activityFilterUrl, 'note');
      }).catch((error) => console.log(error));
    },
    collaboratorParticipant() {
      if (this.formData){

        this.collaborators = [];
        this.participants = [];

        // Collaborator Group by
        this.formData.activity.forEach((element, index) => {
          element.collaborators.forEach((item, index) => {
            this.collaborators.push(item);
          });
        });
        // Participant Group By
        this.formData.activity.forEach((element, index) => {
          element.participants.forEach((item, index) => {
            this.participants.push(item);
          });
        });

      }
    },

  }
}
