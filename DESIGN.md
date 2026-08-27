# Design

<!-- impeccable:design-schema 1 -->
<!-- source: built world of MedicSalud landing (ground truth, not intention) -->

## World

Ciencia viva con calidez humana. Sistema editorial cálido donde el oxígeno (ozono) y la regeneración (plasma) son los motores visuales. Rechaza la plantilla médica fría (azul institucional + grilla de tarjetas de icono) por un lenguaje de orbes orgánicos y línea de pulso, sobre un suelo hueso cálido.

## Palette

| Rol | Hex | Uso |
|---|---|---|
| Bone (suelo) | `#f6f0f0` | Fondo claro principal de la página |
| Bone-2 | `#efe7e7` | Bandas alternas, superficies |
| Bone-3 | `#e7dddd` | Bordes, separadores |
| Ink (tinta) | `#10302A` | Texto, secciones oscuras, footer, hero |
| Ink-soft | `#3C564F` | Texto secundario sobre claro |
| Green (acento brillante) | `#83c352` | Highlight del hero, acentos vivos sobre oscuro |
| Ozone (acento dominante) | `#2E7D32` | Marca, botones, enlaces, motivos, regiones |
| Ozone-deep | `#1F5E22` | Profundidad, hover, hojas de terapia |
| Plasma (acento cálido) | `#E0A33B` | Calidez, bloque "Agenda", acentos |

Estrategia de color: **Committed** — el ozano ocupa regiones completas (hoja PRP, footer, nav CTA) y el plasma lleva el cierre de agendamiento.

## Type

- Display / marca: **Bricolage Grotesque** (600–700), tracking -0.02 a -0.025em.
- Cuerpo / UI: **Hanken Grotesk** (400–700).
- Display máx ~4.6rem (clamp), cuerpo 1.0–1.075rem, measure ~46–60ch.

## Components

- **Nav** pegajosa con borde al hacer scroll; menú móvil despiegable; CTA plasma a la derecha.
- **Hero**: titular editorial + toggle de terapia (Ozonoterapia | PRP) que re-temática el motivo (CSS vars `--motif-a/--motif-b`) y el copy. Motivo SVG: núcleo pulsante + partículas en órbita + línea de pulso (animación `flow`).
- **Terapia-block**: dos bloques editoriales alternos (texto + arte SVG autorado), no grilla de tarjetas.
- **Steps**: proceso de 4 pasos con numeración real (la secuencia lleva información) y línea de conexión ozano→plasma.
- **Manifesto**: banda oscura (ink) con cuadrícula de principios en acento plasma.
- **Trust**: equipo (avatares con iniciales, marcado como ilustrativo) + blockquote.
- **Book**: bloque plasma con formulario demostrativo (validación en cliente, sin backend).
- **Footer**: ink, multi-columna, año dinámico.

## Motion

Un momento autorado: el motivo orbital del hero (respiración del núcleo, órbita lenta, pulso). Resto: hover sutil, subrayado `scaleX`, reveal nulo para no repetir entrada. `prefers-reduced-motion` desactiva todo.

## Accessibility

WCAG AA objetivo: foco visible ozano, selección temada, scrollbar temada, skip-link, contrastes tinta/ink-soft sobre claro y plasma/ozono sobre oscuro. Formulario con labels y `aria-live`.

## Provenance / pendientes

- Contenido de equipo, testimonio y datos de contacto es **ilustrativo** y debe reemplazarse con material real.
- Sin backend: el formulario es demostrativo.
- Tipografías vía Google Fonts (Bricolage Grotesque, Hanken Grotesk).