// MedicSalud — interacciones
(function () {
  "use strict";

  // Nav: estado "stuck" y menú móvil
  var nav = document.getElementById("nav");
  var toggle = document.getElementById("navToggle");
  var menu = document.getElementById("menu");

  var onScroll = function () {
    if (window.scrollY > 8) nav.classList.add("is-stuck");
    else nav.classList.remove("is-stuck");
  };
  window.addEventListener("scroll", onScroll, { passive: true });
  onScroll();

  toggle.addEventListener("click", function () {
    var open = menu.classList.toggle("is-open");
    toggle.setAttribute("aria-expanded", open ? "true" : "false");
  });
  menu.querySelectorAll("a").forEach(function (a) {
    a.addEventListener("click", function () {
      menu.classList.remove("is-open");
      toggle.setAttribute("aria-expanded", "false");
    });
  });

  // Toggle de terapia: cambia copy y re-tematiza el motivo del hero
  var visual = document.querySelector(".hero__visual");
  var opts = document.querySelectorAll(".therapy__opt");
  var desc = document.getElementById("therapyDesc");

  var copy = {
    ozono: "El ozono médico estimula la oxigenación y la respuesta natural del cuerpo. Una terapia limpia, indolora y pensada para ti.",
    prp: "Concentramos las plaquetas de tu propia sangre para activar la regeneración de tejidos. Seguro, ambulatorio y autólogo."
  };
  var theme = {
    ozono: { a: "#83c352", b: "var(--plasma)" },
    prp: { a: "var(--plasma)", b: "#83c352" }
  };

  opts.forEach(function (btn) {
    btn.addEventListener("click", function () {
      var key = btn.getAttribute("data-therapy");
      opts.forEach(function (b) {
        var active = b === btn;
        b.classList.toggle("is-active", active);
        b.setAttribute("aria-pressed", active ? "true" : "false");
      });
      desc.style.opacity = "0";
      setTimeout(function () {
        desc.textContent = copy[key];
        desc.style.opacity = "1";
      }, 200);
      visual.setAttribute("data-theme", key);
      visual.style.setProperty("--motif-a", theme[key].a);
      visual.style.setProperty("--motif-b", theme[key].b);
    });
  });

  // Año en footer
  var year = document.getElementById("year");
  if (year) year.textContent = new Date().getFullYear();

  // Formulario de solicitud de cita
  var form = document.getElementById("bookForm");
  var note = document.getElementById("formNote");
  var isSubmitting = false;

  if (!form || !note) return;

  form.addEventListener("submit", async function (e) {
    e.preventDefault();
    if (isSubmitting) return;

    var name = form.querySelector("#name");
    var phone = form.querySelector("#phone");
    var therapy = form.querySelector("#therapy");
    var message = form.querySelector("#msg");
    var submitButton = form.querySelector('button[type="submit"]');

    if (!name.value.trim() || !phone.value.trim()) {
      note.style.color = "var(--plasma-deep)";
      note.textContent = "Completa tu nombre y un medio de contacto para agendar.";
      (!name.value.trim() ? name : phone).focus();
      return;
    }

    isSubmitting = true;
    form.setAttribute("aria-busy", "true");
    submitButton.disabled = true;
    submitButton.textContent = "Enviando…";
    note.textContent = "";

    try {
      var response = await fetch("api/book-appointment.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          name: name.value.trim(),
          phone: phone.value.trim(),
          therapy: therapy.value,
          msg: message.value.trim()
        })
      });
      var result = await response.json();

      if (!response.ok || !result.ok) throw new Error("No se pudo enviar la solicitud.");

      note.style.color = "var(--ozone-deep)";
      note.textContent = "¡Gracias, " + name.value.trim().split(" ")[0] + "! Te contactaremos pronto para confirmar tu cita.";
      form.reset();
    } catch (error) {
      note.style.color = "var(--plasma-deep)";
      note.textContent = "Hubo un error, lo revisaremos. Gracias";
    } finally {
      isSubmitting = false;
      form.removeAttribute("aria-busy");
      submitButton.disabled = false;
      submitButton.textContent = "Solicitar mi cita";
    }
  });
})();
