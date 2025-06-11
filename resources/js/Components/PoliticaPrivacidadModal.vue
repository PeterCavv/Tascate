<template>
    <p class="mb-6 text-gray-800 leading-relaxed text-sm sm:text-base px-1 sm:px-0">
        En cumplimiento del <strong>Reglamento (UE) 2016/679</strong>, del Parlamento Europeo y del Consejo, de 27 de abril de 2016 (RGPD), y de la Ley Orgánica 3/2018, de 5 de diciembre (LOPDGDD), le informamos de los siguientes aspectos sobre el tratamiento de sus datos personales:
    </p>

    <div class="text-gray-800 text-xs sm:text-sm space-y-6 leading-relaxed px-1 sm:px-0">

        <div>
            <p class="font-semibold mb-1 text-sm sm:text-base">1. Responsable del tratamiento</p>
            <p class="text-xs sm:text-sm">
                TASCATE<br />
                tascate_administracion@gmail.com
            </p>
        </div>

        <div>
            <p class="font-semibold mb-1 text-sm sm:text-base">2. Finalidad del tratamiento</p>
            <p class="text-xs sm:text-sm">
                Los datos personales facilitados a través del formulario de registro de bares serán tratados con la única finalidad de verificar la existencia del establecimiento y la identidad del propietario o representante, para evitar suplantaciones y garantizar la fiabilidad de la información publicada en nuestra plataforma.
            </p>
            <p class="mt-2 text-xs sm:text-sm">
                No se realizarán perfiles, decisiones automatizadas, ni se utilizarán los datos para fines publicitarios o comerciales.
            </p>
        </div>

        <div>
            <p class="font-semibold mb-1 text-sm sm:text-base">3. Legitimación</p>
            <p class="text-xs sm:text-sm">
                La base legal para el tratamiento es el consentimiento del interesado al enviar voluntariamente sus datos para el registro de un establecimiento.
            </p>
        </div>

        <div>
            <p class="font-semibold mb-1 text-sm sm:text-base">4. Destinatarios</p>
            <p class="text-xs sm:text-sm">
                No se cederán datos a terceros, salvo obligación legal.
            </p>
        </div>

        <div>
            <p class="font-semibold mb-1 text-sm sm:text-base">5. Conservación de los datos</p>
            <p class="text-xs sm:text-sm">
                Los datos se conservarán únicamente durante el tiempo necesario para comprobar la veracidad del registro, y posteriormente serán almacenados de forma segura mientras el bar permanezca activo en la plataforma.
            </p>
            <p class="mt-2 text-xs sm:text-sm">
                El usuario puede solicitar la eliminación en cualquier momento, lo que conllevará la baja del establecimiento en la app.
            </p>
        </div>

        <div>
            <p class="font-semibold mb-1 text-sm sm:text-base">6. Derechos del interesado</p>
            <p class="text-xs sm:text-sm">
                Puede ejercer sus derechos de acceso, rectificación, supresión, oposición, limitación del tratamiento y portabilidad enviando una solicitud a: <br />
                <strong>📧 tascate_administracion@gmail.com</strong><br />
                Adjuntando una copia de su documento de identidad para verificar su identidad.
            </p>
        </div>

        <div>
            <p class="font-semibold mb-1 text-sm sm:text-base">7. Medidas de seguridad</p>
            <p class="text-xs sm:text-sm">
                Sus datos serán tratados de forma confidencial y se aplicarán las medidas técnicas y organizativas necesarias para garantizar su seguridad y evitar su alteración, pérdida, tratamiento o acceso no autorizado.
            </p>
        </div>

    </div>

    <div class="mt-8 text-center sm:text-right">
        <Button
            @click="$emit('close')"
            class="bg-blue-600 text-white px-5 py-2 rounded-xl hover:bg-blue-700 transition w-full sm:w-auto"
            aria-label="Cerrar política de privacidad"
        >
            Cerrar
        </Button>
    </div>
</template>
