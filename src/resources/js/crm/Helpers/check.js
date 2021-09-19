class typeCheck {
    constructor(value) {
        this.value = value;
    }
    isString() {
        return typeof this.value === "string" || this.value instanceof String;
    }
    isNumber() {
        return typeof this.value === "number" && isFinite(this.value);
    }
    isObject() {
        return (
            this.value &&
            typeof this.value === "object" &&
            this.value.constructor === Object
        );
    }

    isArray() {
        return (
            this.value &&
            typeof this.value === "object" &&
            this.value.constructor === Array
        );
    }
    isFunction() {
        return typeof this.value === "function";
    }
    isNull() {
        return this.value === null;
    }
    isUndefined() {
        return typeof this.value === "undefined";
    }

    isRegExp() {
        return (
            this.value &&
            typeof this.value === "object" &&
            this.value.constructor === RegExp
        );
    }
    isError() {
        return (
            this.value instanceof Error &&
            typeof this.value.message !== "undefined"
        );
    }
    isDate() {
        return this.value instanceof Date;
    }

    isBoolean() {
        return typeof this.value === "boolean";
    }
    type() {
        return typeof this.value;
    }
}

export const check = value => new typeCheck(value);
