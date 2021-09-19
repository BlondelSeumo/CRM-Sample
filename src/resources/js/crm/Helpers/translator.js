import Vue from 'vue'

Vue.prototype.$placeholder = (subject, key) => {
  return Vue.prototype.$t('place_holder', {
      subject: Vue.prototype.$t(subject).toLowerCase(),
      type: Vue.prototype.$t(key).toLowerCase()
  });
};

Vue.prototype.$fieldTitle = (subject, title = null, infix = null) => {
    if (title)
        return  `${Vue.prototype.$t(subject)}${infix ? Vue.prototype.$t(infix): ' '} ${Vue.prototype.$t(title).toLowerCase()}`;
    return Vue.prototype.$t(subject);
};
