export default class Permission {
    getPermissions() {
        return window.localStorage.getItem('permissions');
    }

    can(ability) {
        if (this.permissions().is_app_admin) {
            return true
        }
        return this.permissions()[ability];
    }

    permissions() {
        return JSON.parse(this.getPermissions());
    }
}
