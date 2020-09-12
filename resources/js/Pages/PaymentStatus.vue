<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <p style="padding: 30px">Nickname - {{ nickname }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import AppLayout from './../Layouts/AppLayout'

export default {
    props: ['nickname'],
    components: {
        AppLayout
    },
}
</script>
