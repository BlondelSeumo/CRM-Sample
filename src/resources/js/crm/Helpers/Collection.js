import { check } from "./check";

export default class Collection {
    constructor(list) {
        if (!check(list).isArray()) {
            throw new Error("Expects an array, found " + typeof list);
        }
        if (list.length == 0) {
            throw new Error("found empty array.");
        }
        list.forEach(obj => {
            if (!check(obj).isObject()) {
                throw new Error(
                    "Expects all collection elements should be an object"
                );
            }
        });

        this.list = list;
        this.filteredList = null;
        this.pro = [];
        this.returnProps = [];
        this.sortKey = null;
    }

    where(key, operator, value, data = this.filtered()) {
        this.filteredList =
            operator == "="
                ? data.filter(item => item[key] == value)
                : operator == ">"
                ? data.filter(item => item[key] > value)
                : operator == "<"
                ? data.filter(item => item[key] < value)
                : operator == "!="
                ? data.filter(item => item[key] != value)
                : data;
        return this;
    }
    whereBetween(key, values) {
        let data = this.filtered();
        this.filteredList = data.filter(
            item => item[key] > values[0] && item[key] < values[1]
        );
        return this;
    }
    whereNotBetween(key, values) {
        let data = this.filtered();
        this.filteredList = data.filter(
            item => item[key] < values[0] || item[key] > values[1]
        );
        return this;
    }
    orWhere(prop, operator, value) {
        let data = this.list;
        let orList =
            operator == "="
                ? data.filter(item => item[prop] == value)
                : operator == ">"
                ? data.filter(item => item[prop] > value)
                : operator == "<"
                ? data.filter(item => item[prop] < value)
                : operator == "!="
                ? data.filter(item => item[prop] != value)
                : data;
        this.filteredList = this.filteredList.concat(orList);
        if (this.sortKey) this.sortBy(this.sortKey);
        return this;
    }
    tap(prop) {
        this.list = this.pluck(prop);
        this.filteredList = null;
        this.pro = [];
        this.returnProps = [];
        this.sortKey = null;

        return this;
    }
    sum(prop) {
        let sum = this.filtered().reduce((acc, item) => {
            acc += item[prop];
            return acc;
        }, 0);

        return sum;
    }
    avg(prop) {
        let sum = this.sum(prop);
        return sum / this.count();
    }
    average(prop) {
        return this.avg(prop);
    }
    filtered() {
        return this.filteredList != null ? this.filteredList : this.list;
    }
    get(...props) {
        let data = this.filtered();
        let returnProps = props < 1 ? this.returnProps : props;
        let subset =
            returnProps.length < 1
                ? data
                : data.reduce((acc, item) => {
                      acc.push(
                          returnProps.reduce((el, key) => {
                              el[key] = item[key];
                              return el;
                          }, {})
                      );
                      return acc;
                  }, []);

        return subset;
    }
    first() {
        let data = this.filtered();
        return data[0];
    }
    props() {
        let data = this.first();
        return Object.keys(data);
    }
    count() {
        let data = this.filtered();
        return data.length;
    }
    nth(index) {
        let data = this.filtered();
        return data[index];
    }
    orginal() {
        return this.list;
    }
    last() {
        let data = this.filtered();
        let index = this.count() - 1;
        return data[index];
    }
    pluck(prop = "id") {
        return this.filtered().map(data => {
            return data[prop];
        });
    }

    fields(...fields) {
        this.fields = fields.length ? fields : ["id", "value"];
        return this;
    }

    merge(list) {
        this.list = this.list.concat(list);
        return this;
    }

    shaper(field = "translated_name", id = "id") {
        if (this.list.length) {
            return this.list.map(data => {
                data.value = data[field];
                data.id = data[id];
                return data;
            });
        }
        return [];
    }

    /**
     * change some keys with new keys
     *
     * @param targetKeys Array
     * @param newKeys Array
     *
     * return Collection
     */

    renameKeys(targetKeys = ["id"], newKeys = ["uuid"]) {
        let data = this.filtered();
        this.filteredList = data.map(obj => {
            for (let i = 0; i <= targetKeys.length; i++) {
                obj[newKeys[i]] = obj[targetKeys[i]];
                delete obj[targetKeys[i]];
            }
            return obj;
        });
        return this;
    }

    /**
     * ordered collection by column name
     *
     * @param key String Column name
     * @param direction String Order Ascending or Descending
     *
     * return Collection
     */

    sortBy(key = "created_at", direction = "asc") {
        this.sortKey = key;
        let data = this.where(key, "!=", null).get();
        let nullData = this.where(key, "=", null, this.orginal()).get();
        this.filteredList =
            direction == "asc"
                ? typeof data[0][key] == "string"
                    ? data.sort((a, b) => {
                          var x = a[key].toLowerCase();
                          var y = b[key].toLowerCase();
                          return x < y ? -1 : x > y ? 1 : 0;
                      })
                    : data.sort((a, b) => a[key] - b[key])
                : typeof data[0][key] == "string"
                ? data.sort((a, b) => {
                      var x = a[key].toLowerCase();
                      var y = b[key].toLowerCase();
                      return x > y ? -1 : x < y ? 1 : 0;
                  })
                : data.sort((a, b) => b[key] - a[key]);
        this.filteredList = this.filteredList.concat(nullData);
        this.sortedList = this.filteredList;
        return this;
    }
    /**
     * find item by id column
     *
     * @param value Column Value
     * @param key String Column Name default is `id`
     *
     * return matched item
     */

    find(value, key = "id") {
        let data = this.filtered();
        return data.find(el => el[key] == value);
    }

    findAll(value, key = "id") {
        return this.where(key, "=", value).get();
    }

    remove(prop) {
        return this.list.filter(l => {
            return String(l[field]) !== String(value);
        });
    }
    removeObject(value, field = 'id') {
        return this.list.filter(l => {
            return String(l[field]) !== String(value);
        })
    }

    min(key) {
        return this.sortBy(key).first();
    }
    max(key) {
        return this.sortBy(key, "desc").first();
    }
    except(...props) {
        let allProps = this.props();
        this.returnProps = allProps.filter(el => !props.includes(el));

        return this;
    }
    only(...props) {
        this.returnProps = props;
        return this;
    }
    prepend(list) {}
    append(list) {
        let data = this.filtered();
        this.filteredList = data.concat(list);

        return this;
    }
}

export const collect = list => new Collection(list);
