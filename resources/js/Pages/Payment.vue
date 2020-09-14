<template>
    <div class="bg-gray-100 min-h-screen">
        <div class="w-full h-48" :style="{'background-image': 'url(/background-donate.jpg)'}">

        </div>
        <div class="container mx-auto">
            <div v-if="this.mode === 'form'" class="w-full mx-auto mt-8 px-64">
                <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-16">
                    <div class="mb-4 flex items-center">
                        <div class="rounded-full h-16 w-16 bg-cover" :style="'background-image: url(' + this.user.profile_image + ')'">
                        </div>
                        <span class=" ml-4">{{ this.user.nickname }}</span>
                    </div>
                    <div class="mb-4">
                        <input
                            class="appearance-none border rounded-lg w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none"
                            id="username" type="text" placeholder="Username" />
                    </div>
                    <div class="mb-6">
                        <input
                            class="appearance-none border rounded-lg w-full pt-4 pb-20 px-4 text-gray-700 leading-tight focus:outline-none"
                            id="message" placeholder="Message" />
                    </div>
                    <div class="flex flex-wrap mb-6 justify-between">
                        <input class="md:w-3/4 appearance-none block w-full text-gray-700 border rounded-lg py-3 px-4 leading-tight focus:outline-none focus:outline-none" id="amount" type="number" placeholder="Amount">
                        <select class="md:w-1/5 block appearance-none w-full border text-gray-700 py-3 px-4 pr-8 rounded-lg leading-tight focus:outline-none focus:outline-none" id="currency">
                            <option>RUB</option>
                            <option>USD</option>
                            <option>EURO</option>
                        </select>
                    </div>
                    <button
                        class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="button" @click="goToPayment">
                        Donate
                    </button>
                </form>
                <p class="text-center text-gray-500 text-xs">
                    &copy;2020 Powered by LiveDonate (github.com/YanGusik)
                </p>
            </div>
            <div v-if="goals !== undefined && this.mode === 'form'"/>
            <div v-if="this.mode === 'payment'" class="w-full mx-auto mt-8 px-64">
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-16">
                    Select a payment method
                </div>
            </div>
            <div v-if="this.mode === 'finish'" class="w-full mx-auto mt-8 px-64">
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-16">
                    Success operation or fail =)
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AppLayout from './../Layouts/AppLayout'

export default {
    components: {
        AppLayout
    },
    props: ['status', 'user', 'goals'],
    data() {
        return {
            mode: "form"
        }
    },
    methods: {
      goToPayment() {
          this.mode = "payment";
      }
    },
    mounted() {
        if (this.status === 'success' || this.status === 'error') {
            this.mode = 'finish';
        }
        console.log(this.status);
        console.log(this.nickname);
        console.log(this.goals);
    }
}
</script>
