import { collect } from "./Collection";
import { check } from "./check";
import { api } from "./api";

let list = [
    { id: 1, name: "potato", price: 4, value: 920 },
    { id: 2, name: "tomato", price: 10, value: 50 },
    { id: 3, name: "apple", price: 25, value: 90 }
];
let res = collect(list)
    .sortBy("value")
    .only("id", "value")
    .get();
let host = "http://127.0.0.1:8000/";
api(host)
    .group("crm/deals")
    .then(res => {
        let data = collect(res[0].data.data).get();
        // .where("type", "!=", "user")
        // .sortBy("class")
        // .only("class")
        // .tap()
        // .sortBy("id")
        // .get();
        console.log(data);
    })
    .catch(err => console.log(err, "error"));

//node -r esm ./resources/js/crm/Helpers/playground.js
