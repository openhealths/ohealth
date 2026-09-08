@php
    $dashboardUrl = legalEntity() ? route('dashboard', [legalEntity()]) : url('/dashboard');
    $patientUrl = route('persons.patient-data', [legalEntity(), 'person' => $personId ?? $prepersonId]);
    $requestsUrl = route($prepersonId !== null ? 'prepersons.prescription-requests' : 'persons.prescription-requests', [legalEntity(), $prepersonId !== null ? 'preperson' : 'person' => $prepersonId ?? $personId]);
    
    $breadcrumbs = [
        ['label' => 'Головна', 'url' => $dashboardUrl],
        ['label' => 'Пацієнти', 'url' => route('persons.index', [legalEntity()])],
        ['label' => $patientFullName, 'url' => $patientUrl],
        ['label' => 'Заявки на рецепти', 'url' => $requestsUrl],
        ['label' => $requestId]
    ];
@endphp

<x-layouts.patient 
    :personId="$personId" 
    :prepersonId="$prepersonId" 
    :patientFullName="$patientFullName" 
    :hideNavigation="true" 
    :breadcrumbs="$breadcrumbs"
    title="Дротаверин 20 мг/мл, р-н для ін'єкцій"
>
    <div class="shift-content pl-3.5 mt-8 max-w-6xl">
        <fieldset class="fieldset">
            
            <div class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-6">Загальна інформація про заявку</div>
            <div class="form-row-2">
                <div class="form-group group">
                    <input value="Доступні ліки" type="text" class="input peer" disabled />
                    <label class="label">Програма</label>
                </div>
                <div class="form-group group">
                    <input value="{{ $requestId }}" type="text" class="input peer" disabled />
                    <label class="label">ID заявки</label>
                </div>
            </div>
            <div class="form-row-2">
                <div class="form-group group">
                    <input value="Активний" type="text" class="input peer" disabled />
                    <label class="label">Статус</label>
                </div>
            </div>

            <!-- "Деталі програми" -->
            <div class="form-row-1 mt-4">
                <div class="form-group group">
                    <textarea class="input peer resize-none" disabled rows="12">Джерело фінансування:
Тип рецептурного бланка:
Обов'язковість використання плану лікування для ЕР:
Типи користувачів, яким дозволено виписувати ЕР:
Перелік спеціальностей лікарів СМД та ПМД, яким дозволено виписувати ЕР/Призначення ПЛ:
Можливість виписувати ЕР на такий самий МНН протягом курсу лікування:
Максимальна тривалість курсу лікування на який може бути виписаний ЕР за програмою:
Можливість виписувати ЕР незалежно від наявності укладеної декларації з пацієнтом:
Можливість виписувати ЕР незалежно від наявності укладеної декларації в закладі, де виписується ЕР:
Можливість часткового погашення ЕР:
Сповіщення пацієнта при операціях з рецептом вимкнено:
Категорії пацієнтів, яким дозволено створення призначення ПЛ:</textarea>
                    <label class="label">Деталі програми</label>
                </div>
            </div>

            <div class="text-xl font-bold text-gray-800 dark:text-gray-200 mt-10 mb-6">Інформація щодо виписаного ЛЗ</div>
            <div class="form-row-2">
                <div class="form-group group">
                    <input value="Дротаверин 20 мг/мл, р-н для ін'єкцій" type="text" class="input peer" disabled />
                    <label class="label">Назва ЛЗ</label>
                </div>
                <div class="form-group group">
                    <input value="Дротаверин (drotaverine): 20,0 мг/мл" type="text" class="input peer" disabled />
                    <label class="label">Складові лікарського засобу (МНН)</label>
                </div>
            </div>
            <div class="form-row-2">
                <div class="form-group group">
                    <input value="Розчин для ін'єкцій" type="text" class="input peer" disabled />
                    <label class="label">Форма випуску ЛЗ</label>
                </div>
                <div class="form-group group">
                    <input value="2 мл" type="text" class="input peer" disabled />
                    <label class="label">Обсяг первинної упаковки виписаного ЛЗ</label>
                </div>
            </div>
            <div class="form-row-2">
                <div class="form-group group">
                    <input value="2 мл" type="text" class="input peer" disabled />
                    <label class="label">Разова доза</label>
                </div>
                <div class="form-group group">
                    <input value="10 мл" type="text" class="input peer" disabled />
                    <label class="label">Добова доза</label>
                </div>
            </div>
            <div class="form-row-1">
                <div class="form-group group">
                    <textarea class="input peer resize-none" disabled rows="2">По 1 (шт) 3 разів на день з 05.02.2026 впродовж 10 днів.</textarea>
                    <label class="label">Сигнатура*</label>
                </div>
            </div>
            <div class="form-row-2">
                <div class="form-group group">
                    <input value="20 мл" type="text" class="input peer" disabled />
                    <label class="label">Кількість виписаного ЛЗ</label>
                </div>
            </div>

            <div class="text-xl font-bold text-gray-800 dark:text-gray-200 mt-10 mb-6">Строки лікування та отримання ЛЗ</div>
            <div class="form-row-2">
                <div class="form-group datepicker-wrapper relative w-full">
                    <input value="02.04.2025" type="text" class="peer input pl-10 appearance-none text-gray-500 dark:text-gray-400" disabled />
                    <label class="wrapped-label">Дата створення заявки</label>
                </div>
            </div>
            <div class="form-row-2">
                <div class="form-group datepicker-wrapper relative w-full">
                    <input value="02.04.2025" type="text" class="peer input pl-10 appearance-none text-gray-500 dark:text-gray-400" disabled />
                    <label class="wrapped-label">Дата початку курсу лікування виписаним ЛЗ</label>
                </div>
                <div class="form-group datepicker-wrapper relative w-full">
                    <input value="02.04.2025" type="text" class="peer input pl-10 appearance-none text-gray-500 dark:text-gray-400" disabled />
                    <label class="wrapped-label">Дата завершення курсу лікування виписаним ЛЗ</label>
                </div>
            </div>
            <div class="form-row-2">
                <div class="form-group datepicker-wrapper relative w-full">
                    <input value="02.04.2025" type="text" class="peer input pl-10 appearance-none text-gray-500 dark:text-gray-400" disabled />
                    <label class="wrapped-label">Дата першого дня, коли можливо отримати виписаний ЛЗ</label>
                </div>
                <div class="form-group datepicker-wrapper relative w-full">
                    <input value="02.04.2025" type="text" class="peer input pl-10 appearance-none text-gray-500 dark:text-gray-400" disabled />
                    <label class="wrapped-label">Дата останнього дня, коли можливо отримати виписаний ЛЗ</label>
                </div>
            </div>

            <div class="text-xl font-bold text-gray-800 dark:text-gray-200 mt-10 mb-6">Інформація про СГУСОЗ, в якому було виписано ЕР</div>
            <div class="form-row-2">
                <div class="form-group group">
                    <input value="КНП Лікарня №2" type="text" class="input peer" disabled />
                    <label class="label">Назва СГУСОЗ</label>
                </div>
                <div class="form-group group">
                    <input value="1234567890" type="text" class="input peer" disabled />
                    <label class="label">код ЄДРПОУ або РНОКПП у разі ФОП</label>
                </div>
            </div>
            <div class="form-row-2">
                <div class="form-group group">
                    <input value="Лікарня №2" type="text" class="input peer" disabled />
                    <label class="label">Публічна назва</label>
                </div>
            </div>

            <div class="text-xl font-bold text-gray-800 dark:text-gray-200 mt-10 mb-6">Інформація про лікаря та пацієнта</div>
            <div class="form-row-2">
                <div class="form-group group">
                    <input value="Шевченко Тарас Григорович" type="text" class="input peer" disabled />
                    <label class="label">ПІБ лікаря</label>
                </div>
                <div class="form-group group">
                    <input value="shevchenko@gmail.com" type="text" class="input peer" disabled />
                    <label class="label">Контактні дані лікаря</label>
                </div>
            </div>
            <div class="form-row-2">
                <div class="form-group group">
                    <input value="{{ $patientFullName }}" type="text" class="input peer" disabled />
                    <label class="label">ПІБ пацієнта</label>
                </div>
                <div class="form-group group">
                    <input value="35" type="text" class="input peer" disabled />
                    <label class="label">Кількість повних років пацієнта</label>
                </div>
            </div>

            <div class="text-xl font-bold text-gray-800 dark:text-gray-200 mt-10 mb-6">Пов'язані медичні записи</div>
            <div class="form-row-2 mb-10">
                <div class="form-group group">
                    <input value="1231-adsadas-aqeqe-casdda" type="text" class="input peer" disabled />
                    <label class="label">Id плану лікування, на основі якого створено ЕР</label>
                </div>
                <div class="form-group group">
                    <input value="1231-adsadas-aqeqe-casdda" type="text" class="input peer" disabled />
                    <label class="label">Id взаємодії, в складі якої створено ЕР</label>
                </div>
            </div>

        </fieldset>

        <!-- Buttons row -->
        <div class="flex items-center gap-4 mt-8 pb-10">
            <a href="{{ $requestsUrl }}" class="button-minor px-6 py-2.5">Назад</a>
            <button type="button" class="button-primary-outline-red px-6 py-2.5">Відмінити заявку</button>
            <button type="button" class="button-primary px-6 py-2.5">Підписати заявку</button>
        </div>
    </div>
</x-layouts.patient>
