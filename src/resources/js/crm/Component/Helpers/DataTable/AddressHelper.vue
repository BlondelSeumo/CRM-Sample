<template>
    <div class="width-200">
        {{ addressDetails }}
    </div>
</template>

<script>
export default {
    name: "AddressDetails",
    props: {
        rowData: Object
    },
    computed: {
        addressDetails() {
            const {address, area, state, city, country, zip_code} = this.rowData
            const addresses = [address, area, state, city]
            let stringAddresses = '';
            let filterAddresses = addresses.map(address => {
                stringAddresses += address ? `${address}, ` : '';
            })
            stringAddresses += country?.name ? `${country?.name}${zip_code ? '-' : ''}` : '';
            stringAddresses += zip_code ? `${zip_code}` : '';
            return stringAddresses.replace(/,\s*$/, "");
        }
    },
}
</script>
