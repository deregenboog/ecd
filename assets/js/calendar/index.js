// assets/js/calendar/index.js
import { Calendar } from "@fullcalendar/core";
import interactionPlugin from "@fullcalendar/interaction";
import dayGridPlugin from "@fullcalendar/daygrid";
import nlLocale from '@fullcalendar/core/locales/nl';
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";




import "./index.css";

document.addEventListener("DOMContentLoaded", () => {
    let calendarEl = document.getElementById("calendar-holder");
    var calendarInitialized = false; // Flag to track if the custom event has been dispatched


    // let modalWindowId = document.getElementById("modal-window");
    let { eventsUrl } = calendarEl.dataset;
    let { calendarId } = calendarEl.dataset;
    let { entityId } = calendarEl.dataset;

    let calendar = new Calendar(calendarEl, {
        editable: true,
        locale: nlLocale,
        weekNumbers: true,
        eventSources: [
            {
                url: eventsUrl,
                method: "POST",
                extraParams: {
                    filters: JSON.stringify({
                        "calendar-id": calendarId,
                        "entity-id": entityId,
                    }),
                },
                failure: () => {
                    alert("There was an error while fetching FullCalendar!");
                },
                success: () => {

                },
            },
        ],
        headerToolbar: {
            left: "prev,next",
            center: "title",
            right: "today"
        },
        height: "auto",
        initialView: "dayGridMonth",

        datesSet: function(dateInfo) {
            // Define a custom event for calendar initialization
            let options = {
                detail: {
                    calendar: calendar,
                    dateInfo: dateInfo,
                }
            }


            const calendarInitializedEvent = new CustomEvent('calendarInitialized', options);
            const calendarDatesSetEvent = new CustomEvent('calendarDatesSet', options);

            if (!calendarInitialized) {
                // Dispatch the custom event only once
                document.dispatchEvent(calendarInitializedEvent);
                calendarInitialized = true; // Set the flag to true after dispatching
            }
            else {
                document.dispatchEvent(calendarDatesSetEvent);
            }
        },

        navLinks: false, // can click day/week names to navigate views
        plugins: [ interactionPlugin, dayGridPlugin ],
        timeZone: "UTC",
    });

    calendar.render();

    calendarEventData.modalWindow = $('#' + calendarEventData.modalWindowId);
});

document.addEventListener('calendarInitialized', function(e) {
    // Access the additional data from the event's detail property
    // console.log('Calendar has been initialized with date info:', e.detail.dateInfo);
    // Example: You can now use the date info or other details as needed
    let calendar = e.detail.calendar;
    let eventHandlers = {
        saveEvent: saveEvent,
        removeEvent: removeEvent
    }

    function handleDateClick({ dateStr }) {
        let dayEvents = calendar.getEvents().filter(event => {
            return event.startStr == dateStr;
        });
        if (dayEvents.length > 0) {
            let event = dayEvents[0];
            if(!event.id){
                alert("Event gevonden maar er gaat iets niet goed.");
                return;
            }
            handleEventClick({event});
            return;
        }
        const createUrl = `${calendarEventData.indexUrl.replace(/(.*\/)(\?.*)/, '$1create')}/${dateStr}/${calendarEventData.entityId}/`;
        actionHandler(createUrl);
    }

    function handleEventClick({ event }) {
        const editUrl = `${calendarEventData.indexUrl.replace(/(.*\/)(\?.*)/, '$1edit')}/${event.id}`;
        actionHandler(editUrl, event);
    }

    function actionHandler(url, event) {
        $.get(url).done(data => {
            modalOpen(data.formHtml);
            moveButtons();
            modalAddEvents(url);
        });
    }

    function moveButtons()
    {
        //Since the buttons are inserted in the form, but should appear as part of the modal Window.
        // Keep only the original button (#sluiten) in the footer
        $('.modal-footer button:not(#sluiten)').remove();

        // Select all button elements in the .modal-content
        const buttonsInModalContent = $('.modal-body button');

        // Iterate through the buttons
        buttonsInModalContent.each(function(i, button) {
            // Append each button to .modal-footer
            $('.modal-footer').append(button);
        });
    }

    function modalOpen(html) {
       calendarEventData.modalWindow.find('.modal-body').html(html);
       calendarEventData.modalWindow.modal('show');
    }

    /**
     * Attaches event handlers to buttons inside a modal.
     * The callbacks can be cofnigured in the form as attr=>data-callback
     *
     * @param {string} url - The URL to pass as a parameter to the callback functions.
     */
    function modalAddEvents(url) {

        $('.modal-content button').each(function() {
            const callback = $(this).data('callback');
            if (callback && eventHandlers[callback]) { // check if callback function exists in your namespace
                $(this).off('click').click(function(e) {
                    e.preventDefault();
                    $(this).prop('disabled',true);
                    eventHandlers[callback](e, url); // call function from your namespace
                });
            } else if(callback){
                console.log("Callback niet gevonden in eventHandlers: ".callback);
            }
        });
    }

    let options = {
        detail: {
            calendar: calendar
        }
    }


    const calendarSavedEventEvent = new CustomEvent('calendarSavedEvent', options);
    const calendarRemovedEventEvent = new CustomEvent('calendarRemovedEvent', options);

    function saveEvent(e, url)
    {
        e.stopPropagation();
        e.preventDefault();
        const form = calendarEventData.modalWindow.find(' form')[0];
        $.ajax({
            type: $(form).attr('method'),
            url: url,
            data: $(form).serialize(),
            success: function(data) {
                if (data.success) {
                    calendarEventData.modalWindow.modal('hide');
                    document.dispatchEvent(calendarSavedEventEvent);

                    calendar.refetchEvents();


                } else {
                    alert("Fout tijdens opslaan.");
                }
            }
        });
    }

    function removeEvent(e, url)
    {
        e.stopPropagation();
        e.preventDefault();
        const form = calendarEventData.modalWindow.find('form')[0];
        url = url.replace("edit","delete");
        $.ajax({
            type: $(form).attr('method'),
            url: url,
            data: $(form).serialize(),
            success: function(data) {
                if (data.success) {
                    calendarEventData.modalWindow.modal('hide');
                    calendar.refetchEvents();
                    document.dispatchEvent(calendarRemovedEventEvent);
                } else {
                    alert("Fout tijdens opslaan.");
                }
            }
        });
    }

    calendar.on("dateClick", handleDateClick);
    calendar.on("eventClick", handleEventClick);
});