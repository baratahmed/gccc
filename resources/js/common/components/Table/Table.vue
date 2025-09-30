<script setup>

</script>

<template>
    <div class="table-responsive">
        <table id="example2" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <slot name="tableHead"/>
                </tr>
            </thead>
            <tbody>
               <slot />
            </tbody>
        </table>
    </div>
</template>