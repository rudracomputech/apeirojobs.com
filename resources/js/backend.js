
import jQuery from 'jquery';
import select2 from 'select2';
import "select2-tailwindcss-v4-theme/dist/select2-tailwindcss-theme-plain.min.css";
import 'simplebar'; // Initialize SimpleBar globally via data attributes
import 'simplebar/dist/simplebar.css'; // Import the default styling

window.$ = window.jQuery = jQuery;
select2($);



    $('select').each(function () {

    $(this).select2({
        theme: 'tailwindcss-4',
        width: '100%',
        tags: $(this).data('tags') === true || $(this).data('tags') === 'true',

        createTag: function (params) {

            let term = params.term.trim();

            if (!term) {
                return null;
            }

            return {
                id: term,
                text: term + ' (Add New)',
                newTag: true
            };
        },

        insertTag: function (data, tag) {
            data.unshift(tag);
        }
    });

});



document.addEventListener("DOMContentLoaded", () => {

   // Sidebar toggle
   const toggleBtn = document.getElementById("toggleSidebar");
   const sidebar = document.getElementById("sidebar");

   const openClasses = ["w-[264px]", "min-w-[264px]", "opacity-100"];
   const collapsedClasses = ["w-0", "min-w-0", "opacity-0"];

   // Set initial sidebar state based on viewport size
   function initializeSidebarState() {
      const isDesktop = window.innerWidth >= 1024; // lg breakpoint
      if (isDesktop) {
         // Open on desktop
         sidebar.classList.remove(...collapsedClasses);
         sidebar.classList.add(...openClasses);
         toggleBtn.setAttribute("aria-expanded", "true");
      } else {
         // Closed on mobile
         sidebar.classList.remove(...openClasses);
         sidebar.classList.add(...collapsedClasses);
         toggleBtn.setAttribute("aria-expanded", "false");
      }
   }

   // Initialize on load
   initializeSidebarState();

   // Re-initialize on resize
   window.addEventListener("resize", initializeSidebarState);

   toggleBtn.addEventListener("click", () => {
      const isExpanded = toggleBtn.getAttribute("aria-expanded") === "true";
      if (isExpanded) {
         sidebar.classList.remove(...openClasses);
         sidebar.classList.add(...collapsedClasses);
         toggleBtn.setAttribute("aria-expanded", "false");
      } else {
         sidebar.classList.remove(...collapsedClasses);
         sidebar.classList.add(...openClasses);
         toggleBtn.setAttribute("aria-expanded", "true");
      }
   });
   // 

   // searchbar
   const searchToggleBtn = document.getElementById("searchToggle");
   const searchBox = document.getElementById("searchBox");
   const wrapper = document.getElementById("searchWrapper");
if (searchToggleBtn && searchBox && wrapper) {
   searchToggleBtn.addEventListener("click", (e) => {
      e.stopPropagation();

      // Show as dropdown on mobile
      searchBox.classList.toggle("max-lg:hidden");
      searchBox.classList.toggle("absolute");
      searchBox.classList.toggle("right-0");
      searchBox.classList.toggle("w-64");
      searchBox.classList.toggle("z-50");
   });
}
// notification dropdown
   const notificationToggle = document.getElementById("notificationToggle");
   const notificationMenu = document.getElementById("dropdown-notification");

   notificationToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      notificationMenu.classList.toggle("hidden");
      notificationMenu.classList.toggle("block");
      const isExpanded = notificationToggle.getAttribute("aria-expanded") === "true";
      notificationToggle.setAttribute("aria-expanded", String(!isExpanded));
   });

   // Close on Escape key (Essential for WCAG)
   document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
         notificationMenu.classList.add("hidden");
         notificationMenu.classList.remove("block");
         notificationToggle.setAttribute("aria-expanded", "false");
         notificationToggle.focus();
      }
   });

   // Close when clicking outside
   document.addEventListener("click", (e) => {
      if (!notificationMenu.contains(e.target) && e.target !== notificationToggle) {
         notificationMenu.classList.add("hidden");
         notificationMenu.classList.remove("block");
         notificationToggle.setAttribute("aria-expanded", "false");
      }
   });
   // 


   // profile dropdown
   const toggle = document.getElementById("dropdown-toggle");
   const menu = document.getElementById("dropdown-menu");
   const links = menu.querySelectorAll(".dropdown-item");

   function show() {
      menu.classList.remove("hidden");
      menu.classList.add("block");
      toggle.setAttribute("aria-expanded", "true");
   }

   function hide() {
      menu.classList.add("hidden");
      menu.classList.remove("block");
      toggle.setAttribute("aria-expanded", "false");
   }

   toggle.addEventListener("click", (e) => {
      e.stopPropagation();
      const isExpanded = toggle.getAttribute("aria-expanded") === "true";
      isExpanded ? hide() : show();
   });

   // Close on Escape key (Essential for WCAG)
   document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
         hide();
         toggle.focus();
      }
   });

   // Close when clicking outside
   document.addEventListener("click", (e) => {
      if (!menu.contains(e.target) && e.target !== toggle) hide();
   });

   // Close when a link is clicked
   links.forEach(link => link.addEventListener("click", hide));
   // 

   // collapsible submenus
   document.querySelectorAll(".collapsible-toggle").forEach((toggle) => {

      toggle.addEventListener("click", function () {
         const menu = this.nextElementSibling; // the submenu <ul>
         const arrowIcon = this.querySelector(".arrow");
         const isOpen = menu.offsetHeight !== 0;

         if (isOpen) {
            menu.style.maxHeight = "0px";
            arrowIcon.classList.add("-rotate-90");
            this.setAttribute("aria-expanded", "false");
         } else {
            menu.style.maxHeight = menu.scrollHeight + "px";
            arrowIcon.classList.remove("-rotate-90");
            this.setAttribute("aria-expanded", "true");
         }
      });
   });

});

 const buttons = document.querySelectorAll('.dismiss-btn');

  buttons.forEach(button => {
    button.addEventListener('click', () => {
      // Find the closest parent with role="alert" and remove it
      const alert = button.closest('[role="alert"]');
      if (alert) {
        alert.remove();
      }
    });
  });


