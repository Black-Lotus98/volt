<template>
    <div :class="($t('lang') === 'ar') ? 'text-right' : 'text-left'"
        class="flex-grow rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="border-b border-stroke py-4 px-7 dark:border-strokedark">
            <h3 class="font-medium text-black dark:text-white">
                <slot name="title" />
            </h3>
        </div>
        <div class="p-7">
            <div class="mb-3">
                <slot name="content" />

            </div>
            <div class="flex flex-wrap items-start justify-between gap-3 sm:flex-nowrap">
                <slot name="footer" />
            </div>
        </div>
    </div>
</template>
