<template>
    <div>
        Alert
        <img :src="gif" v-show="gifShow">
    </div>
</template>

<script>

export default {
    props: ['user', 'token'],
    data() {
        return {
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


        setInterval(()=>{
            this.showMessage()
        },7000);
    },
    methods: {
        showMessage() {
            console.log(this.queue);
            let message = this.queue.pop();
            if (message !== undefined) {
                this.audio.volume = 0.3;
                this.audio.play();
                this.gifShow = true;
            }
        },
    }
}
</script>
