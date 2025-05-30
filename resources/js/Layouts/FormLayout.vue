<template>
    <div class="min-h-screen w-full bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center p-4 sm:p-6">
        <div class="w-full max-w-3xl rounded-2xl shadow-lg bg-white overflow-hidden">
            <div class="p-6 sm:p-8 lg:p-10">
                <div class="flex flex-col md:flex-row h-full">
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-6">
                            <slot name="header">
                                <div>
                                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                                        <slot name="title">Tascate</slot>
                                    </h1>
                                    <p class="text-gray-500 mt-2">
                                        <slot name="subtitle">Complete todos los campos requeridos (*) </slot>
                                    </p>
                                </div>
                            </slot>
                            <slot name="header-actions"></slot>
                        </div>

                        <div class="space-y-6">
                            <slot></slot>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col items-center">
                            <slot name="motivational-phrase">
                                <div>
                                    <Fieldset legend="Tascate">
                                        <p class="m-0">Aquí cada tasca tiene una historia… ¿Cuál será la tuya?</p>
                                    </Fieldset>
                                </div>
                            </slot>

                        </div>
                    </div>

                    <div class="hidden md:block ml-8 w-32 flex-shrink-0 bg-gray-50 rounded-lg overflow-hidden border-l border-gray-200">
                        <img
                            src="/images/espiral.svg"
                            alt="Decoración"
                            class="h-full w-full object-cover"
                            style="transform: scale(2.2);"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
