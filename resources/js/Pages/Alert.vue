<template>
    <div class="h-full w-full min-h-screen" style="background: transparent">
        <button class="btn btn-primary" @click="this.fakeMessage">Fake message</button>
        <img :src="gif" v-show="gifShow" style="position: absolute;left: 50%;margin-left: -300px;">
        <div class="flex" v-show="gifShow" style="position: absolute;top: 60%;font-size: 70px;color: orange;text-align: center; ">
            <span style="animation: tada 2s infinite ease-in-out" v-for="(char, index) in this.message" :key="index">{{char}}</span>
        </div>
    </div>
</template>

<script>

import Button from "../Jetstream/Button";
export default {
    components: {Button},
    props: ['user', 'token'],
    data() {
        return {
            message: '',
            queue: [],
            audio: new Audio('http://static.donationalerts.ru/uploads/sounds/2/point.mp3'),
            gif: 'http://static.donationalerts.ru/uploads/images/2/zombie.gif',
            gifShow: false
        }
    },
    computed: {
        channel() {
            console.log('alert.' + this.token);
            return window.Echo.channel('alert.' + this.token);
        }
    },
    mounted() {
        this.channel
            .listen('\\App\\Events\\SendDonationNotification', (data) => {
                console.log(data);
                this.queue.push(`${data.nickname} - ${data.message}. ${data.amount} Руб`);
            });


        setInterval(() => {
            this.showMessage()
        }, 8000);
    },
    methods: {
        fakeMessage() {
            let data = {
                nickname: "Test",
                message: "TestMessage",
                amount: 100
            }
            console.log(data);
            this.queue.push(`${data.nickname} - ${data.message}. ${data.amount} Руб`);
        },
        showMessage() {
            let message = this.queue.pop();
            if (message !== undefined) {
                console.log(message);
                this.audio.volume = 0;
                this.audio.play();
                this.gifShow = true;
                this.message = message;
                setTimeout(() => {
                    this.gifShow = false;
                }, 7000);
            }
        },
    }
}
</script>

<style>

@keyframes tada {
    from {
        -webkit-transform: scale3d(1, 1, 1);
        transform: scale3d(1, 1, 1);
    }

    10%, 20% {
        -webkit-transform: scale3d(.9, .9, .9) rotate3d(0, 0, 1, -3deg);
        transform: scale3d(.9, .9, .9) rotate3d(0, 0, 1, -3deg);
    }

    30%, 50%, 70%, 90% {
        -webkit-transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, 3deg);
        transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, 3deg);
    }

    40%, 60%, 80% {
        -webkit-transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, -3deg);
        transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, -3deg);
    }

    to {
        -webkit-transform: scale3d(1, 1, 1);
        transform: scale3d(1, 1, 1);
    }
}

.tada {
    -webkit-animation-name: tada;
    animation-name: tada;
}
</style>
