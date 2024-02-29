// assets/js/calendar/index.js
import { Calendar } from "@fullcalendar/core";
import interactionPlugin from "@fullcalendar/interaction";
import dayGridPlugin from "@fullcalendar/daygrid";
import nlLocale from '@fullcalendar/core/locales/nl';
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";




import "./index.css"; // this will create a calendar.css file reachable to 'encore_entry_link_tags'


document.addEventListener("DOMContentLoaded", () => {
    let calendarEl = document.getElementById("calendar-holder");
    var calendarInitialized = false; // Flag to track if the custom event has been dispatched


    // let modalWindowId = document.getElementById("modal-window");
    let { eventsUrl } = calendarEl.dataset;
    let { calendarId } = calendarEl.dataset;

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
                    }) // pass your parameters to the subscriber
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
            const calendarDateSetEvent = new CustomEvent('calendarDateSet', options);

            if (!calendarInitialized) {
                // Dispatch the custom event only once
                document.dispatchEvent(calendarInitializedEvent);
                calendarInitialized = true; // Set the flag to true after dispatching
            }
            else {
                document.dispatchEvent(calendarDateSetEvent);
            }
        },

        navLinks: true, // can click day/week names to navigate views
        plugins: [ interactionPlugin, dayGridPlugin ],
        timeZone: "UTC",
    });
    calendar.render();

});