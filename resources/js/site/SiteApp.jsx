import { SiteContext } from './context.js';
import Layout from './layout/Layout.jsx';
import About from './pages/About.jsx';
import Affiliate from './pages/Affiliate.jsx';
import CareerShow from './pages/CareerShow.jsx';
import Careers from './pages/Careers.jsx';
import Compliance from './pages/Compliance.jsx';
import Contact from './pages/Contact.jsx';
import CookiePolicy from './pages/CookiePolicy.jsx';
import Faqs from './pages/Faqs.jsx';
import Home from './pages/Home.jsx';
import NotFound from './pages/NotFound.jsx';
import PostShow from './pages/PostShow.jsx';
import PostsIndex from './pages/PostsIndex.jsx';
import ResourceShow from './pages/ResourceShow.jsx';
import ResourcesIndex from './pages/ResourcesIndex.jsx';
import ServiceShow from './pages/ServiceShow.jsx';
import ServicesIndex from './pages/ServicesIndex.jsx';
import Software from './pages/Software.jsx';
import Specialities from './pages/Specialities.jsx';
import Team from './pages/Team.jsx';

const pages = {
    home: Home,
    about: About,
    team: Team,
    specialities: Specialities,
    software: Software,
    compliance: Compliance,
    faqs: Faqs,
    'cookie-policy': CookiePolicy,
    contact: Contact,
    affiliate: Affiliate,
    'services.index': ServicesIndex,
    'services.show': ServiceShow,
    'resources.index': ResourcesIndex,
    'resources.show': ResourceShow,
    'posts.index': PostsIndex,
    'posts.show': PostShow,
    'careers.index': Careers,
    'careers.show': CareerShow,
};

export default function SiteApp({ page, props, shared, flash, csrfToken }) {
    const Page = pages[page] ?? NotFound;

    return (
        <SiteContext.Provider value={{ shared, flash, csrfToken }}>
            <Layout><Page {...props} /></Layout>
        </SiteContext.Provider>
    );
}
