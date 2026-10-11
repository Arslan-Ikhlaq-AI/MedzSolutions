import { useId } from 'react';

/**
 * Brand illustrations for medical billing topics, drawn in the logo palette. The same seed always
 * draws the same picture, so cards stay stable until a real "image" is set in config/site.php.
 */
const tones = [
    { bg: '#EAF2FA', glow: '#D9E8F7', main: '#2A6BAF', second: '#23908C', soft: '#D3E3F3', grid: 'rgba(15,42,71,0.06)', outline: '#0F2A47', road: '#FFFFFF' },
    { bg: '#E7F4F3', glow: '#D3ECE9', main: '#23908C', second: '#2A6BAF', soft: '#CBE7E3', grid: 'rgba(15,42,71,0.06)', outline: '#0F2A47', road: '#FFFFFF' },
    { bg: '#102C4C', glow: '#15406E', main: '#4F9AD8', second: '#5CC6C1', soft: '#1D4F86', grid: 'rgba(255,255,255,0.06)', outline: '#CFE0F2', road: 'rgba(255,255,255,0.12)', dark: true },
];

const GOLD = '#E1A43A';
const GOLD_DEEP = '#C98A22';
const INK = '#0F2A47';
const LINE = '#C7D3E0';

function hashString(text) {
    let hash = 2166136261;

    for (const character of text) {
        hash ^= character.charCodeAt(0);
        hash = Math.imul(hash, 16777619);
    }

    return hash >>> 0;
}

function createRandom(seed) {
    let state = seed;

    return () => {
        state = (state + 0x6D2B79F5) | 0;
        let value = Math.imul(state ^ (state >>> 15), 1 | state);
        value = (value + Math.imul(value ^ (value >>> 7), 61 | value)) ^ value;

        return ((value ^ (value >>> 14)) >>> 0) / 4294967296;
    };
}

function Plus({ x, y, size = 8, color = GOLD }) {
    return <path d={`M${x} ${y - size}v${size * 2}M${x - size} ${y}h${size * 2}`} stroke={color} strokeWidth="3.5" strokeLinecap="round" />;
}

function Coin({ x, y, id }) {
    return (
        <g transform={`translate(${x} ${y})`}>
            <circle r="42" fill={GOLD} filter={`url(#${id}-shadow)`} />
            <circle r="32" fill="none" stroke="#fff" strokeOpacity=".55" strokeWidth="3" />
            <path d="M8-13c-3-4-15-4-17 1-2 6 17 6 17 13 0 6-13 7-18 2M0-21v42" fill="none" stroke="#fff" strokeWidth="5" strokeLinecap="round" />
        </g>
    );
}

function Seal({ x, y, tone, id }) {
    return (
        <g transform={`translate(${x} ${y})`}>
            <circle r="56" fill="#fff" filter={`url(#${id}-shadow)`} />
            <circle r="44" fill={tone.second} />
            <path d="M-18 1-5 14 20-12" fill="none" stroke="#fff" strokeWidth="9" strokeLinecap="round" strokeLinejoin="round" />
        </g>
    );
}

function Lines({ x, y, widths, gap = 18, first = INK }) {
    return widths.map((width, index) => (
        <rect key={index} x={x} y={y + index * gap} width={width} height={index === 0 ? 10 : 7} rx="4" fill={index === 0 ? first : LINE} opacity={index === 0 ? 0.78 : 1} />
    ));
}

function Claim({ tone, random, id }) {
    return (
        <>
            <g fill="none" stroke={tone.outline} strokeWidth="6" strokeLinecap="round" opacity=".75">
                <path d="M118 84v56a40 40 0 0 0 80 0V84" />
                <path d="M158 180v52c0 38 24 62 56 66" />
            </g>
            <circle cx="118" cy="82" r="7" fill={tone.outline} opacity=".75" />
            <circle cx="198" cy="82" r="7" fill={tone.outline} opacity=".75" />
            <circle cx="222" cy="300" r="20" fill="#fff" stroke={tone.outline} strokeOpacity=".75" strokeWidth="6" />
            <g transform="rotate(-3 400 280)">
                <rect x="250" y="70" width="300" height="410" rx="16" fill="#fff" filter={`url(#${id}-shadow)`} />
                <path d="M266 70h268a16 16 0 0 1 16 16v38H250V86a16 16 0 0 1 16-16Z" fill={tone.main} />
                <rect x="274" y="88" width="120" height="10" rx="5" fill="#fff" opacity=".92" />
                <rect x="274" y="105" width="74" height="7" rx="3.5" fill="#fff" opacity=".6" />
                <circle cx="520" cy="97" r="14" fill="#fff" opacity=".22" />
                <path d="M520 90v14M513 97h14" stroke="#fff" strokeWidth="3" strokeLinecap="round" />
                {[0, 1, 2, 3, 4].map((row) => (
                    <g key={row} transform={`translate(274 ${148 + row * 50})`}>
                        <rect width={44 + random() * 20} height="6" rx="3" fill={LINE} />
                        <rect y="12" width="118" height="22" rx="6" fill="#EEF3F8" />
                        <rect x="136" width={40 + random() * 24} height="6" rx="3" fill={LINE} />
                        <rect x="136" y="12" width="116" height="22" rx="6" fill="#EEF3F8" />
                        {row === 1 && <rect x="146" y="20" width="56" height="6" rx="3" fill={tone.main} opacity=".65" />}
                        {row === 3 && <rect x="10" y="20" width="70" height="6" rx="3" fill={tone.second} opacity=".75" />}
                    </g>
                ))}
                <rect x="274" y="410" width="252" height="44" rx="10" fill="#EEF3F8" />
                <rect x="290" y="428" width="84" height="8" rx="4" fill={INK} opacity=".55" />
                <rect x="446" y="424" width="64" height="16" rx="8" fill={tone.second} />
            </g>
            <Seal x={566} y={372} tone={tone} id={id} />
            <Coin x={214} y={432} id={id} />
        </>
    );
}

function Analytics({ tone, random, id }) {
    const bars = Array.from({ length: 7 }, () => 50 + random() * 108);
    const trend = bars.map((_, index) => [219 + index * 58, 300 - index * 20 - random() * 16]);
    const [lastX, lastY] = trend[trend.length - 1];

    return (
        <>
            <rect x="160" y="80" width="480" height="350" rx="18" fill="#fff" filter={`url(#${id}-shadow)`} />
            <Lines x={190} y={110} widths={[150, 96]} gap={22} />
            <rect x="500" y="106" width="110" height="30" rx="15" fill="#EEF3F8" />
            <rect x="504" y="110" width="54" height="22" rx="11" fill={tone.main} />
            {[0, 1, 2, 3].map((line) => <rect key={line} x="190" y={178 + line * 56} width="420" height="1.5" fill="#E6EDF4" />)}
            {bars.map((height, index) => (
                <rect key={index} x={206 + index * 58} y={402 - height} width="26" height={height} rx="7" fill={index % 2 ? tone.second : tone.main} opacity={index === bars.length - 1 ? 1 : 0.85} />
            ))}
            <polyline points={trend.map((point) => point.join(',')).join(' ')} fill="none" stroke={GOLD} strokeWidth="4" strokeLinecap="round" strokeLinejoin="round" />
            <circle cx={lastX} cy={lastY} r="8" fill="#fff" stroke={GOLD} strokeWidth="4" />
            <g transform="translate(520 372)">
                <rect width="176" height="78" rx="16" fill="#fff" filter={`url(#${id}-shadow)`} />
                <circle cx="38" cy="39" r="21" fill={tone.second} opacity=".14" />
                <path d="M38 49V29m-8 8 8-8 8 8" fill="none" stroke={tone.second} strokeWidth="4" strokeLinecap="round" strokeLinejoin="round" />
                <Lines x={70} y={26} widths={[78, 54]} />
            </g>
        </>
    );
}

function InsuranceCard({ tone, random, id }) {
    return (
        <>
            <g transform="rotate(-7 360 260)">
                <rect x="160" y="130" width="400" height="250" rx="22" fill={tone.main} filter={`url(#${id}-shadow)`} />
                <rect x="160" y="130" width="400" height="250" rx="22" fill={`url(#${id}-sheen)`} />
                <circle cx="222" cy="194" r="26" fill="#fff" opacity=".18" />
                <path d="M222 182v24M210 194h24" stroke="#fff" strokeWidth="4" strokeLinecap="round" />
                <rect x="262" y="180" width="160" height="12" rx="6" fill="#fff" opacity=".92" />
                <rect x="262" y="202" width="104" height="8" rx="4" fill="#fff" opacity=".6" />
                <rect x="196" y="282" width="210" height="11" rx="5.5" fill="#fff" opacity=".88" />
                <rect x="196" y="304" width="150" height="8" rx="4" fill="#fff" opacity=".55" />
                <rect x="196" y="322" width="120" height="8" rx="4" fill="#fff" opacity=".55" />
                <rect x="466" y="282" width="62" height="46" rx="9" fill={GOLD} />
                <path d="M466 305h62M497 282v46" stroke="#fff" strokeOpacity=".4" strokeWidth="2" />
            </g>
            <g transform="translate(470 300)">
                <rect width="214" height="150" rx="18" fill="#fff" filter={`url(#${id}-shadow)`} />
                {[0, 1, 2].map((row) => (
                    <g key={row} transform={`translate(22 ${26 + row * 40})`}>
                        <circle cx="12" cy="12" r="12" fill={tone.second} />
                        <path d="m6 12 4 4 8-8" fill="none" stroke="#fff" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round" />
                        <rect x="34" y="3" width={86 + random() * 40} height="8" rx="4" fill={INK} opacity=".7" />
                        <rect x="34" y="16" width="70" height="6" rx="3" fill={LINE} />
                    </g>
                ))}
            </g>
        </>
    );
}

function Calendar({ tone, id }) {
    return (
        <>
            <rect x="200" y="96" width="370" height="350" rx="20" fill="#fff" filter={`url(#${id}-shadow)`} />
            <path d="M220 96h330a20 20 0 0 1 20 20v52H200v-52a20 20 0 0 1 20-20Z" fill={tone.main} />
            <rect x="236" y="124" width="120" height="12" rx="6" fill="#fff" opacity=".9" />
            <rect x="498" y="120" width="44" height="20" rx="10" fill="#fff" opacity=".25" />
            <rect x="262" y="78" width="14" height="38" rx="7" fill={INK} />
            <rect x="494" y="78" width="14" height="38" rx="7" fill={INK} />
            {[0, 1, 2, 3].map((row) => [0, 1, 2, 3, 4, 5, 6].map((column) => {
                const isBooked = row === 1 && column === 4;
                const isDone = row === 2 && column === 1;

                return (
                    <g key={`${row}-${column}`}>
                        <rect x={228 + column * 46} y={192 + row * 58} width="34" height="34" rx="9" fill={isBooked ? GOLD : isDone ? tone.second : '#EEF3F8'} />
                        {(isBooked || isDone) && (
                            <path d={`m${236 + column * 46} ${209 + row * 58} 6 6 11-11`} fill="none" stroke="#fff" strokeWidth="3.5" strokeLinecap="round" strokeLinejoin="round" />
                        )}
                    </g>
                );
            }))}
            <g transform="translate(590 404)">
                <circle r="66" fill="#fff" filter={`url(#${id}-shadow)`} />
                <circle r="52" fill="none" stroke={tone.main} strokeWidth="6" />
                <path d="M0-30V0l22 14" fill="none" stroke={INK} strokeWidth="6" strokeLinecap="round" strokeLinejoin="round" />
                <circle r="6" fill={GOLD} />
            </g>
        </>
    );
}

function Credential({ tone, id }) {
    return (
        <>
            <g transform="translate(112 238) rotate(-8)">
                <rect width="122" height="164" rx="14" fill="#fff" filter={`url(#${id}-shadow)`} />
                <rect x="45" y="-12" width="32" height="22" rx="6" fill={INK} />
                <circle cx="61" cy="60" r="26" fill="#EEF3F8" />
                <circle cx="61" cy="53" r="10" fill={tone.main} />
                <path d="M43 76c4-11 32-11 36 0" fill={tone.main} />
                <rect x="26" y="104" width="70" height="8" rx="4" fill={INK} opacity=".7" />
                <rect x="34" y="120" width="54" height="6" rx="3" fill={LINE} />
                <rect x="30" y="138" width="62" height="10" rx="5" fill={tone.second} opacity=".85" />
            </g>
            <rect x="230" y="100" width="420" height="320" rx="16" fill="#fff" filter={`url(#${id}-shadow)`} />
            <rect x="248" y="118" width="384" height="284" rx="10" fill="none" stroke={tone.soft} strokeWidth="3" />
            <circle cx="440" cy="162" r="22" fill="#EEF3F8" />
            <path d="M440 152v20M430 162h20" stroke={tone.main} strokeWidth="4" strokeLinecap="round" />
            <rect x="360" y="198" width="160" height="14" rx="7" fill={INK} opacity=".82" />
            <rect x="326" y="226" width="228" height="8" rx="4" fill={LINE} />
            <rect x="346" y="244" width="188" height="8" rx="4" fill={LINE} />
            <path d="M288 338c18-26 34 8 52-8s28 18 46 2 22-16 30-4" fill="none" stroke={tone.main} strokeWidth="3.5" strokeLinecap="round" />
            <rect x="284" y="356" width="150" height="2" fill={LINE} />
            <g transform="translate(560 330)">
                <path d="M-26 20-38 80-16 68-4 86 6 30Z" fill={GOLD_DEEP} />
                <path d="M26 20 38 80 16 68 4 86-6 30Z" fill={GOLD_DEEP} />
                <circle r="44" fill={GOLD} filter={`url(#${id}-shadow)`} />
                <circle r="33" fill="none" stroke="#fff" strokeOpacity=".6" strokeWidth="3" strokeDasharray="6 5" />
                <path d="M-14 0-3 11 16-10" fill="none" stroke="#fff" strokeWidth="7" strokeLinecap="round" strokeLinejoin="round" />
            </g>
        </>
    );
}

function Security({ tone, id }) {
    return (
        <>
            <g transform="rotate(-7 300 280)">
                <rect x="190" y="124" width="230" height="300" rx="16" fill="#fff" filter={`url(#${id}-shadow)`} />
                <Lines x={216} y={156} widths={[120, 160, 140, 150, 110]} gap={24} />
            </g>
            <g transform="rotate(6 470 280)">
                <rect x="380" y="112" width="230" height="300" rx="16" fill="#fff" filter={`url(#${id}-shadow)`} />
                <Lines x={406} y={144} widths={[110, 150, 130, 160, 120]} gap={24} />
            </g>
            <path d="M400 128 514 172v86c0 72-49 123-114 148-65-25-114-76-114-148v-86Z" fill={`url(#${id}-shield)`} filter={`url(#${id}-shadow)`} />
            <path d="M400 156 488 190v68c0 56-37 97-88 117" fill="none" stroke="#fff" strokeOpacity=".25" strokeWidth="4" />
            <path d="m356 268 30 30 60-62" fill="none" stroke="#fff" strokeWidth="14" strokeLinecap="round" strokeLinejoin="round" />
            <g transform="translate(598 414)">
                <circle r="46" fill="#fff" filter={`url(#${id}-shadow)`} />
                <rect x="-18" y="-6" width="36" height="28" rx="6" fill={GOLD} />
                <path d="M-11-6v-8a11 11 0 0 1 22 0v8" fill="none" stroke={GOLD} strokeWidth="6" />
            </g>
        </>
    );
}

function Support({ tone, random, id }) {
    return (
        <>
            <rect x="150" y="104" width="330" height="134" rx="26" fill="#fff" filter={`url(#${id}-shadow)`} />
            <path d="M196 232 182 270l44-36Z" fill="#fff" />
            <circle cx="204" cy="160" r="26" fill="#EEF3F8" />
            <circle cx="204" cy="153" r="10" fill={tone.second} />
            <path d="M188 176c3-9 29-9 32 0" fill={tone.second} />
            <Lines x={246} y={140} widths={[180, 150, 110]} />
            <rect x="320" y="272" width="336" height="122" rx="26" fill={tone.main} filter={`url(#${id}-shadow)`} />
            <path d="M610 390 628 426l-46-34Z" fill={tone.main} />
            <rect x="350" y="304" width={200 + random() * 60} height="10" rx="5" fill="#fff" opacity=".92" />
            <rect x="350" y="326" width="190" height="8" rx="4" fill="#fff" opacity=".6" />
            <rect x="350" y="344" width="140" height="8" rx="4" fill="#fff" opacity=".6" />
            <rect x="170" y="300" width="104" height="48" rx="24" fill="#fff" filter={`url(#${id}-shadow)`} />
            {[0, 1, 2].map((dot) => <circle key={dot} cx={198 + dot * 24} cy="324" r="6" fill={tone.main} opacity={0.45 + dot * 0.25} />)}
            <g transform="translate(612 150)">
                <circle r="50" fill={GOLD} filter={`url(#${id}-shadow)`} />
                <path d="M-24 8V2a24 24 0 0 1 48 0v6" fill="none" stroke="#fff" strokeWidth="6" strokeLinecap="round" />
                <rect x="-31" y="2" width="13" height="23" rx="5" fill="#fff" />
                <rect x="18" y="2" width="13" height="23" rx="5" fill="#fff" />
                <path d="M24 25c0 9-7 13-17 13" fill="none" stroke="#fff" strokeWidth="4" strokeLinecap="round" />
            </g>
        </>
    );
}

function Pattern({ tone, id }) {
    return (
        <>
            <circle cx="400" cy="280" r="190" fill="none" stroke={tone.main} strokeOpacity=".14" strokeWidth="38" />
            <path d="M400 90a190 190 0 0 1 190 190" fill="none" stroke={tone.main} strokeWidth="38" strokeLinecap="round" />
            <path d="M400 470a190 190 0 0 1-190-190" fill="none" stroke={tone.second} strokeWidth="38" strokeLinecap="round" />
            <circle cx="400" cy="280" r="106" fill="#fff" filter={`url(#${id}-shadow)`} />
            <path d="M400 238v84M358 280h84" stroke={tone.main} strokeWidth="18" strokeLinecap="round" />
            <g transform="translate(560 410)">
                <rect width="168" height="66" rx="16" fill="#fff" filter={`url(#${id}-shadow)`} />
                <circle cx="34" cy="33" r="17" fill={tone.second} />
                <path d="m26 33 5 5 10-10" fill="none" stroke="#fff" strokeWidth="3.5" strokeLinecap="round" strokeLinejoin="round" />
                <Lines x={62} y={22} widths={[76, 52]} />
            </g>
        </>
    );
}

function MapScene({ tone, id }) {
    return (
        <>
            <g fill="none" stroke={tone.road} strokeWidth="20" strokeLinecap="round">
                <path d="M-60 180C200 130 360 260 860 196" />
                <path d="M120 620C220 380 420 330 520-60" />
                <path d="M-60 430C260 400 520 476 860 372" />
            </g>
            <rect x="560" y="236" width="120" height="80" rx="12" fill={tone.glow} />
            <rect x="180" y="250" width="110" height="90" rx="12" fill={tone.glow} />
            <path d="M210 470C300 420 380 400 470 318" fill="none" stroke={tone.main} strokeWidth="6" strokeDasharray="2 14" strokeLinecap="round" />
            <circle cx="470" cy="304" r="74" fill={tone.main} opacity=".1" />
            <circle cx="470" cy="304" r="42" fill={tone.main} opacity=".16" />
            <path d="M470 304c-34-38-52-66-52-94a52 52 0 0 1 104 0c0 28-18 56-52 94Z" fill={tone.main} filter={`url(#${id}-shadow)`} />
            <circle cx="470" cy="208" r="22" fill="#fff" />
            <path d="M470 198v20M460 208h20" stroke={tone.main} strokeWidth="4" strokeLinecap="round" />
            <g transform="translate(120 96)">
                <rect width="230" height="92" rx="18" fill="#fff" filter={`url(#${id}-shadow)`} />
                <circle cx="46" cy="46" r="24" fill={GOLD} />
                <path d="M46 34v12l9 6" fill="none" stroke="#fff" strokeWidth="4" strokeLinecap="round" strokeLinejoin="round" />
                <Lines x={84} y={32} widths={[118, 84]} />
            </g>
        </>
    );
}

const scenes = {
    claim: Claim,
    analytics: Analytics,
    idcard: InsuranceCard,
    calendar: Calendar,
    credential: Credential,
    security: Security,
    support: Support,
    pattern: Pattern,
    map: MapScene,
    document: Claim,
    dashboard: Analytics,
    browser: Analytics,
    phone: Support,
    abstract: Pattern,
};

export default function Artwork({ kind = 'pattern', seed = 'medz-solutions', tone }) {
    const id = `art${useId().replace(/[^a-zA-Z0-9]/g, '')}`;
    const seedValue = hashString(seed);
    const palette = tones[(tone ?? seedValue) % tones.length];
    const random = createRandom(seedValue);
    const Scene = scenes[kind] ?? Pattern;

    return (
        <svg className="artwork" viewBox="0 0 800 560" preserveAspectRatio="xMidYMid meet" aria-hidden="true" focusable="false">
            <defs>
                <pattern id={`${id}-grid`} width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M40 0H0v40" fill="none" stroke={palette.grid} strokeWidth="1" />
                </pattern>
                <linearGradient id={`${id}-shield`} x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0" stopColor={palette.main} />
                    <stop offset="1" stopColor={palette.second} />
                </linearGradient>
                <linearGradient id={`${id}-sheen`} x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0" stopColor="#fff" stopOpacity=".24" />
                    <stop offset=".6" stopColor="#fff" stopOpacity="0" />
                </linearGradient>
                <filter id={`${id}-shadow`} x="-30%" y="-30%" width="160%" height="170%">
                    <feDropShadow dx="0" dy="14" stdDeviation="16" floodColor="#0F2A47" floodOpacity={palette.dark ? 0.45 : 0.16} />
                </filter>
            </defs>
            <rect x="-1600" y="-1600" width="4000" height="3760" fill={palette.bg} />
            <rect x="-1600" y="-1600" width="4000" height="3760" fill={`url(#${id}-grid)`} />
            <circle cx="660" cy="100" r="190" fill={palette.glow} />
            <circle cx="110" cy="510" r="130" fill={palette.glow} opacity=".75" />
            <Plus x={50 + random() * 30} y={46 + random() * 30} />
            <Plus x={712 + random() * 30} y={472 + random() * 30} size={6} />
            <Scene tone={palette} random={random} id={id} />
        </svg>
    );
}
