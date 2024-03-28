<?php

namespace Drupal\digital_marketing_checklist;

use Drupal\checklistapi\ChecklistapiChecklist;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Link;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Url;
use Drupal\views\Plugin\views\field\MultiItemsFieldHandlerInterface;

/**
 * Class DigitalMarketingChecklist.
 */
class DigitalMarketingChecklist implements DigitalMarketingChecklistInterface {

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Drupal\Core\Extension\ModuleHandlerInterface definition.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Drupal\Core\Render\RendererInterface definition.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * Drupal\Core\Messenger\MessengerInterface definition.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs a new ProductionChecklist object.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    ModuleHandlerInterface     $module_handler,
    RendererInterface          $renderer,
    ConfigFactoryInterface     $config,
    MessengerInterface         $messenger
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->moduleHandler = $module_handler;
    $this->renderer = $renderer;
    $this->config = $config;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableSections() {
    return [
      'digital_marketing_plan' => t('Digital Marketing Plan'),
      'drupal_system' => t('Drupal system'),
      'website_user_experience' => t('Website’s User Experience'),
      'content_marketing' => t('Content Marketing'),
      'social_media_marketing' => t('Social Media Marketing'),
      'sem_seo' => t('Search Engine Marketing: SEO'),
      'sem_ppc' => t('Search Engine Marketing: PPC'),
      'affiliate_marketing' => t('Affiliate Marketing'),
      'display_advertising' => t('Display Advertising'),
      'email_marketing' => t('Email Marketing'),
      'digital_pr' => t('Digital PR'),
      'influencer_marketing' => t('Influencer Marketing'),
      'crm_marketing' => t('CRM Marketing'),
      'ecommerce_marketing' => t('Ecommerce Marketing'),
      'measuring_analyzing' => t('Measuring & Analyzing'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableSectionsItems() {
    $sections = [];

    // Digital Marketing Plan.
    $sections['digital_marketing_plan'] = [
      '#title' => t('Digital Marketing Plan'),
      '#description' => '<h2>' . t('Write your Digital Marketing Plan with at least the following sections.') . '</h2>',

      'digital_marketing_plan_1' => ["#title" => t("Support the overall goals and strategies set on the company's overall business plan")],
      'digital_marketing_plan_2' => ["#title" => t("Have a clear Brand Positioning")],
      'digital_marketing_plan_3' => ["#title" => t("Understand your customers and build personas")],
      'digital_marketing_plan_4' => ["#title" => t("Map the Customer Journey(s)")],
      'digital_marketing_plan_5' => ["#title" => t("Create a SWOT analysis of the business/brand")],
      'digital_marketing_plan_6' => ["#title" => t("Set the budget for the marketing plan")],
      'digital_marketing_plan_7' => ["#title" => t("Establish the primary objectives")],
      'digital_marketing_plan_8' => ["#title" => t("Define the strategy to reach the set objectives")],
      'digital_marketing_plan_9' => ["#title" => t("Define the marketing tactics that implement the strategy")],
      'digital_marketing_plan_10' => ["#title" => t("Set KPIs and Metrics for the overall plan")],
      'digital_marketing_plan_11' => ["#title" => t("Create an action plan for each of the marketing tactics")],


    ];

    // Drupal system.
    $sections['drupal_system'] = [
      '#title' => t('Drupal system'),
      '#description' => '<h2>' . t('Make sure your Drupal system is updated, secured, and performant.') . '</h2>',

      'drupal_system_1' => ["#title" => t("Check Drupal system wide status and reports for errors")],
      'drupal_system_2' => ["#title" => t("Check available updates to core and contributed modules")],
      'drupal_system_3' => ["#title" => t("Review the file system and Drupal permissions")],
      'drupal_system_4' => ["#title" => t("Make sure Drupal is protected against and free of spam, malware and unwanted software")],
      'drupal_system_5' => ["#title" => t("Have the automatic backup system in place")],
      'drupal_system_6' => ["#title" => t("Do a speed/load audit and optimize it")],
      'drupal_system_7' => ["#title" => t("Secure the site with HTTPS")],
      'drupal_system_8' => ["#title" => t("Install helper modules")],

    ];

    // Website’s User Experience.
    $sections['website_user_experience'] = [
      '#title' => t('Website’s User Experience'),
      '#description' => '<h2>' . t('It is critical to create an excellent website user experience.') . '</h2>',

      'website_user_experience_1' => ["#title" => t("Make sure the purpose of the website is immediately clear")],
      'website_user_experience_2' => ["#title" => t("Assure the design and layout of the website are consistent and appropriate for your brand")],
      'website_user_experience_3' => ["#title" => t("Ensure the website is responsive and easy to use on different devices")],
      'website_user_experience_4' => ["#title" => t("Guarantee it is available on the user's native language")],
      'website_user_experience_5' => ["#title" => t("Make sure the pages are checked against accessibility standards")],
      'website_user_experience_6' => ["#title" => t("Ensure that there are other elements like prizes, testimonials, third-party references or endorsements, and social proof that help build trust")],
      'website_user_experience_7' => ["#title" => t("Guarantee that there''s an \"About us\" page")],
      'website_user_experience_8' => ["#title" => t("Assure that there's a search function")],
      'website_user_experience_9' => ["#title" => t("Provide special 404, 403, and maintenance pages")],

    ];

    // Content Marketing.
    $sections['content_marketing'] = [
      '#title' => t('Content Marketing'),
      '#description' => '<h2>' . t('There is no way around this: content – excellent content – is what stands out amidst the tsunami of information that floods the Internet today.') . '</h2>',

      'content_marketing_1' => ["#title" => t("Set your Content Marketing goals and KPIs")],
      'content_marketing_2' => ["#title" => t("Research topics/keywords to target in your content")],
      'content_marketing_3' => ["#title" => t("Perform keyword research for popular keywords and long-tail keywords")],
      'content_marketing_4' => ["#title" => t("Have content form each stage of the content funnel (for example: awareness, consideration, decision, and post-purchase)")],
      'content_marketing_5' => ["#title" => t("Have your content professionally created if you don't have the expertise or time")],
      'content_marketing_6' => ["#title" => t("Adapt the content to your defined personas")],
      'content_marketing_7' => ["#title" => t("Guarantee the content is produced clearly in a common language that the audience will understand")],
      'content_marketing_8' => ["#title" => t("Make sure your content is clear and concise, easy to read, well-written, and well-structured")],
      'content_marketing_9' => ["#title" => t("Make sure the tone of the content is consistent with the brand")],
      'content_marketing_10' => ["#title" => t("Have a content style guide")],
      'content_marketing_11' => ["#title" => t("Guarantee your content is actionable")],
      'content_marketing_12' => ["#title" => t("Adapt your content to the publishing channel in size and form")],
      'content_marketing_13' => ["#title" => t("Make sure there are links to your different related contents")],
      'content_marketing_14' => ["#title" => t("Guarantee the content is easily scannable, having short paragraphs, subheadings, lists, and images")],
      'content_marketing_15' => ["#title" => t("Promote the content using the channels defined in your digital marketing plan")],
      'content_marketing_16' => ["#title" => t("Create a content calendar that gives you an overview of what is planned for your content creation for the next months")],
      'content_marketing_17' => ["#title" => t("Have a mix of different content types: articles, videos, infographics, podcasts, charts, FAQs, etc.")],
      'content_marketing_18' => ["#title" => t("Have content that is fresh but also evergreen")],
      'content_marketing_19' => ["#title" => t("Research your competitors' content strategy")],
      'content_marketing_20' => ["#title" => t("Check for duplicated content")],
      'content_marketing_21' => ["#title" => t("Create SEO-friendly content")],
      'content_marketing_22' => ["#title" => t("Proofread all your content")],
      'content_marketing_23' => ["#title" => t("Monitor the performance of your published content")],
      'content_marketing_24' => ["#title" => t("Find your best-performing content and build on them")],
      'content_marketing_25' => ["#title" => t("Check your content for comments and reply to them")],
      'content_marketing_26' => ["#title" => t("Track, measure, and optimize your campaigns")],

    ];

    // Content Marketing.
    $sections['social_media_marketing'] = [
      '#title' => t('Social Media Marketing'),
      '#description' => '<h2>' . t('Social networking is one of the major forces behind digital marketing nowadays.') . '</h2>',

      'social_media_marketing_1' => ["#title" => t("Set your Social Media Marketing goals and KPIs")],
      'social_media_marketing_2' => ["#title" => t("Create your business' profiles in each targeted social network")],
      'social_media_marketing_3' => ["#title" => t("Make sure your Social Media Marketing strategy is aligned with your Content Marketing Strategy")],
      'social_media_marketing_4' => ["#title" => t("Keep your profiles complete and updated")],
      'social_media_marketing_5' => ["#title" => t("Guarantee that the contact information includes website, phone number, email, and address")],
      'social_media_marketing_6' => ["#title" => t("Choose a profile photo (logo) and cover photo that are appropriated to each social network style")],
      'social_media_marketing_7' => ["#title" => t("Have a consistent look, feel, tone, and values across all platforms")],
      'social_media_marketing_8' => ["#title" => t("Post frequently and different types of content")],
      'social_media_marketing_9' => ["#title" => t("Have a social media calendar")],
      'social_media_marketing_10' => ["#title" => t("Identify the best days and times for posting in each social media network")],
      'social_media_marketing_11' => ["#title" => t("Adjust the level of frequency that each social media channel requires")],
      'social_media_marketing_12' => ["#title" => t("Monitor your competitors")],
      'social_media_marketing_13' => ["#title" => t("Add tracking tags to posts that link to your website")],
      'social_media_marketing_14' => ["#title" => t("Use scheduling tools")],
      'social_media_marketing_15' => ["#title" => t("Monitor the performance of your organic posts")],
      'social_media_marketing_16' => ["#title" => t("Engage with your audience")],
      'social_media_marketing_17' => ["#title" => t("Check your posts for comments and reply to them")],
      'social_media_marketing_18' => ["#title" => t("Install social media native analytics and conversion scripts")],
      'social_media_marketing_19' => ["#title" => t("Set up social media advertising accounts")],
      'social_media_marketing_20' => ["#title" => t("Monitor your paid campaign performance metrics")],
      'social_media_marketing_21' => ["#title" => t("Make sure your social media addresses are easily found on your company's stationary, website, email signatures, etc.")],
      'social_media_marketing_22' => ["#title" => t("Guarantee that your company's profile has your main products and services listed")],
      'social_media_marketing_23' => ["#title" => t("Make good use of hashtags")],
      'social_media_marketing_24' => ["#title" => t("Monitor and respond to mentions of your business.")],
      'social_media_marketing_25' => ["#title" => t("Invite your team members and friends to share company-related content on their own social networks")],
      'social_media_marketing_26' => ["#title" => t("Plan the budget for your paid campaigns")],
      'social_media_marketing_27' => ["#title" => t("Verify ad guidelines are followed")],
      'social_media_marketing_28' => ["#title" => t("Take advantage of the most recent trends and memes")],
      'social_media_marketing_29' => ["#title" => t("Track, measure, and optimize your campaigns")],

    ];

    // Search Engine Marketing: SEO.
    $sections['sem_seo'] = [
      '#title' => t('Search Engine Marketing: SEO'),
      '#description' => '<h2>' . t('SEO focuses on the organic side of SEM, it\'s the set of strategies which aim to improve the positioning of a website on the results of search engines.') . '</h2>',

      'sem_seo_1' => ["#title" => t("Set your SEO goals and KPIs")],
      'sem_seo_2' => ["#title" => t("Set up Google Search Console")],
      'sem_seo_3' => ["#title" => t("Generate and submit an XML Sitemap")],
      'sem_seo_4' => ["#title" => t("Check the robots.txt file")],
      'sem_seo_5' => ["#title" => t("Check if your site appears in the search engine results page")],
      'sem_seo_6' => ["#title" => t("Identify your target keywords")],
      'sem_seo_7' => ["#title" => t("Check and fix the crawl errors reported by Google Search Console")],
      'sem_seo_8' => ["#title" => t("Check and fix broken internal and outbound links")],
      'sem_seo_9' => ["#title" => t("Add schema markup for rich snippets")],
      'sem_seo_10' => ["#title" => t("Check and edit all title tags")],
      'sem_seo_11' => ["#title" => t("Check that the URL structure follows the navigation hierarchy")],
      'sem_seo_12' => ["#title" => t("Use Breadcrumbs")],
      'sem_seo_13' => ["#title" => t("Have a canonical URL per page")],
      'sem_seo_14' => ["#title" => t("Set up non www to www redirect or vice versa")],
      'sem_seo_15' => ["#title" => t("Have the right Meta Tags")],
      'sem_seo_16' => ["#title" => t("Use 301 redirects for changed URLs")],
      'sem_seo_17' => ["#title" => t("Make sure your copywriting is SEO optimized")],
      'sem_seo_18' => ["#title" => t("Guarantee that the images have ALT tags")],
      'sem_seo_19' => ["#title" => t("Make sure the files have descriptive, keyword-filled filenames")],
      'sem_seo_20' => ["#title" => t("Find new Link Building opportunities")],
      'sem_seo_21' => ["#title" => t("Keep track of the rankings for your most important keywords")],
      'sem_seo_22' => ["#title" => t("Find events you can sponsor and get a link back")],
      'sem_seo_23' => ["#title" => t("Add your website to your signature posts on forums and other social media platforms")],
      'sem_seo_24' => ["#title" => t("Add your website to industry's directories")],
      'sem_seo_25' => ["#title" => t("Find and fix duplicate content issues")],
      'sem_seo_26' => ["#title" => t("Pursue unlinked mentions and ask for a link")],

    ];

    // Search Engine Marketing: PPC.
    $sections['sem_ppc'] = [
      '#title' => t('Search Engine Marketing: PPC'),
      '#description' => '<h2>' . t('PPC focuses on the paid side of SEM.') . '</h2>',

      'sem_ppc_1' => ["#title" => t("Set your PPC goals and KPIs")],
      'sem_ppc_2' => ["#title" => t("Define the targeting")],
      'sem_ppc_3' => ["#title" => t("Optimize your Landing Pages")],
      'sem_ppc_4' => ["#title" => t("Research your competitors")],
      'sem_ppc_5' => ["#title" => t("Conduct keyword research")],
      'sem_ppc_6' => ["#title" => t("Verify ad guidelines are followed")],
      'sem_ppc_7' => ["#title" => t("Effectively structure your search advertising campaign")],
      'sem_ppc_8' => ["#title" => t("Divide keywords in themed Ad groups")],
      'sem_ppc_9' => ["#title" => t("Develop ad copy incorporating benefits and offers")],
      'sem_ppc_10' => ["#title" => t("Choose which Ad Extensions to enable")],
      'sem_ppc_11' => ["#title" => t("Define your placements")],
      'sem_ppc_12' => ["#title" => t("Review keyword match type settings")],
      'sem_ppc_13' => ["#title" => t("Define your URLS (display and destination)")],
      'sem_ppc_14' => ["#title" => t("Create a list of negative terms")],
      'sem_ppc_15' => ["#title" => t("Implement conversion and remarketing tracking code")],
      'sem_ppc_16' => ["#title" => t("Set a daily budget cap")],
      'sem_ppc_17' => ["#title" => t("Pause underperforming keywords")],
      'sem_ppc_18' => ["#title" => t("Ensure each ad has a clear call-to-action")],
      'sem_ppc_19' => ["#title" => t("Track, measure, and optimize your campaigns")],

    ];

    // Affiliate Marketing.
    $sections['affiliate_marketing'] = [
      '#title' => t('Affiliate Marketing'),
      '#description' => '<h2>' . t('It\'s a type of performance-based marketing where your affiliates are only paid if the visitor does the action that was established on the affiliate agreement.') . '</h2>',

      'affiliate_marketing_1' => ["#title" => t("Set your Affiliate Marketing goals and KPIs")],
      'affiliate_marketing_2' => ["#title" => t("Choose your commission model – PPC, PPS or PPL")],
      'affiliate_marketing_3' => ["#title" => t("Create an \"Affiliate Program\" page on your website")],
      'affiliate_marketing_4' => ["#title" => t("Create a Trademark Usage Policy for affiliates")],
      'affiliate_marketing_5' => ["#title" => t("Create an \"Affiliate Agreement\"")],
      'affiliate_marketing_6' => ["#title" => t("Create official promotional material (banners, logos, emails, etc.)")],
      'affiliate_marketing_7' => ["#title" => t("Sign up to the right affiliate networks")],
      'affiliate_marketing_8' => ["#title" => t("Submit your Affiliate Program to Affiliate Directories")],
      'affiliate_marketing_9' => ["#title" => t("Have a system in place to manage affiliates and their earnings and payments")],
      'affiliate_marketing_10' => ["#title" => t("Have an affiliate tracking system")],
      'affiliate_marketing_11' => ["#title" => t("Have a fraud detecting system in place")],
      'affiliate_marketing_12' => ["#title" => t("Have a promotion strategy for your Affiliate Program")],
      'affiliate_marketing_13' => ["#title" => t("Monitor the performance of your affiliates")],
      'affiliate_marketing_14' => ["#title" => t("Reward your best affiliates")],
    ];

    // Display Advertising.
    $sections['display_advertising'] = [
      '#title' => t('Display Advertising'),
      '#description' => '<h2>' . t('Display Advertising consists of buying ad space on a website for a fee. It\'s the correspondence between buying an ad on a physical magazine or newspaper.') . '</h2>',

      'display_advertising_1' => ["#title" => t("Set your Display Advertising goals and KPIs")],
      'display_advertising_2' => ["#title" => t("Plan your budget")],
      'display_advertising_3' => ["#title" => t("Research potential websites to advertise on")],
      'display_advertising_4' => ["#title" => t("Choose your media buying options (directly, media agencies or programmatic platforms)")],
      'display_advertising_5' => ["#title" => t("Decide if you need an Ad Server")],
      'display_advertising_6' => ["#title" => t("Research which websites your customers visit")],
      'display_advertising_7' => ["#title" => t("Create compelling banner ads in all standard sizes")],
      'display_advertising_8' => ["#title" => t("Verify ad guidelines are followed")],
      'display_advertising_9' => ["#title" => t("Have a remarketing campaign")],
      'display_advertising_10' => ["#title" => t("Optimize your Landing Pages")],
      'display_advertising_11' => ["#title" => t("Have a clear Call-to-Action")],
      'display_advertising_12' => ["#title" => t("Always display your logo")],
      'display_advertising_13' => ["#title" => t("Add UTM parameters to your campaigns")],
      'display_advertising_14' => ["#title" => t("Define the campaign frequency capping")],
      'display_advertising_15' => ["#title" => t("Track, measure, and optimize your campaigns")],
    ];

    // Email Marketing.
    $sections['email_marketing'] = [
      '#title' => t('Email Marketing'),
      '#description' => '<h2>' . t('As a mass communication tool, the email allows a unique individualization of your message, without interfering with your marketing strategy\'s budget.') . '</h2>',

      'email_marketing_1' => ["#title" => t("Set your Email Marketing goals and KPIs")],
      'email_marketing_2' => ["#title" => t("Build an email list from information stored about your existing customers")],
      'email_marketing_3' => ["#title" => t("Welcome new subscribers")],
      'email_marketing_4' => ["#title" => t("A/B test your newsletters")],
      'email_marketing_5' => ["#title" => t("Segment your contact list and adjust the content sent")],
      'email_marketing_6' => ["#title" => t("Have a responsive email template")],
      'email_marketing_7' => ["#title" => t("Check your email preheader")],
      'email_marketing_8' => ["#title" => t("Understand the importance of a great subject line")],
      'email_marketing_9' => ["#title" => t("Make sure the email and sender name are clearly identifiable")],
      'email_marketing_10' => ["#title" => t("Proofread and check spelling and grammar")],
      'email_marketing_11' => ["#title" => t("Include contact details and social media links")],
      'email_marketing_12' => ["#title" => t("Decide on your optimal frequency per type of email")],
      'email_marketing_13' => ["#title" => t("Add an unsubscription link to keep your emails GDPR-compliant")],
      'email_marketing_14' => ["#title" => t("Add UTM parameters to email links")],
      'email_marketing_15' => ["#title" => t("Personalize the content of the email to each user")],
      'email_marketing_16' => ["#title" => t("Monitor email bounces and remove them from your list")],
      'email_marketing_17' => ["#title" => t("Resend the email campaigns to non-openers")],
      'email_marketing_18' => ["#title" => t("Scheduled the email campaign for a strategic time and day")],
      'email_marketing_19' => ["#title" => t("Always test the email before sending")],
      'email_marketing_20' => ["#title" => t("Have a Sender Policy Framework (SPF) record in your domain")],
      'email_marketing_21' => ["#title" => t("Have a DomainKeys Identified Mail (DKIM) record in your domain")],
      'email_marketing_22' => ["#title" => t("Add sign up forms to your newsletter whenever possible")],
      'email_marketing_23' => ["#title" => t("Have your email sent by an Email Service Provider (ESP)")],
      'email_marketing_24' => ["#title" => t("Track, measure, and optimize your campaigns")],

    ];

    // Digital PR.
    $sections['digital_pr'] = [
      '#title' => t('Digital PR'),
      '#description' => '<h2>' . t('Digital PR is the outreach and networking to journalists, bloggers, and other content creators to increase your brand awareness and establish your brand\'s authority by making it newsworthy to their digital media platforms. ') . '</h2>',

      'digital_pr_1' => ["#title" => t("Set your Digital PR goals and KPIs")],
      'digital_pr_2' => ["#title" => t("Decide the audience you are trying to influence")],
      'digital_pr_3' => ["#title" => t("Create a list of journalists that report on your industry")],
      'digital_pr_4' => ["#title" => t("Identify media contacts and build a relationship with them")],
      'digital_pr_5' => ["#title" => t("Make sure you are communicating clear and consistent messages")],
      'digital_pr_6' => ["#title" => t("Take advantage of natural PR opportunities, such as product launches, new employees, new customers, or business milestones")],
      'digital_pr_7' => ["#title" => t("Have a spokesperson that is confident, calm, and media-trained")],
      'digital_pr_8' => ["#title" => t("Create a news angle for your message making it newsworthy")],
      'digital_pr_9' => ["#title" => t("Pitch your organization's press release to your media list")],
      'digital_pr_10' => ["#title" => t("Monitor the performance of all your PR campaigns")],
    ];

    // Influencer Marketing.
    $sections['influencer_marketing'] = [
      '#title' => t('Influencer Marketing'),
      '#description' => '<h2>' . t('Influencer Marketing is a spin on the old marketing tactic of partnership with a celebrity that endorses your brand in commercials.') . '</h2>',

      'influencer_marketing_1' => ["#title" => t("Set your Influencer Marketing goals and KPIs")],
      'influencer_marketing_2' => ["#title" => t("Find top influencers in your industry and follow them")],
      'influencer_marketing_3' => ["#title" => t("Keep track of the engagement they have on social media or other platforms")],
      'influencer_marketing_4' => ["#title" => t("Keep track if they are sharing sponsored content and with what frequency")],
      'influencer_marketing_5' => ["#title" => t("Interact naturally with the content they share, reshare it when appropriate")],
      'influencer_marketing_6' => ["#title" => t("Group the influencers you are prospecting and divide them by mega, macro, micro, and nano influencers")],
      'influencer_marketing_7' => ["#title" => t("Find the influencers most appropriate form of direct contact")],
      'influencer_marketing_8' => ["#title" => t("Always communicate personally and make it clear how the influencer will benefit from your partnership")],
      'influencer_marketing_9' => ["#title" => t("Plan your budget")],
      'influencer_marketing_10' => ["#title" => t("Make sure you follow legal disclosure guidelines of your country laws")],
      'influencer_marketing_11' => ["#title" => t("When possible, prefer product gifting to monetary compensations")],
      'influencer_marketing_12' => ["#title" => t("Measure the results of the influencer's endorsements")],
    ];

    // CRM Marketing.
    $sections['crm_marketing'] = [
      '#title' => t('CRM Marketing'),
      '#description' => '<h2>' . t('CRM stands for Customer Relationship Management; it\'s the process of managing interactions with existing customers, as well as past and potential customers.') . '</h2>',

      'crm_marketing_1' => ["#title" => t("Set your CRM Marketing goals and KPIs")],
      'crm_marketing_2' => ["#title" => t("Question yourself: Am I delivering on the brand's promise?")],
      'crm_marketing_3' => ["#title" => t("Create delight moments in every step of your customer's journey")],
      'crm_marketing_4' => ["#title" => t("Have a profile for each prospects and customers")],
      'crm_marketing_5' => ["#title" => t("Record all transactional data with prospect and customers")],
      'crm_marketing_6' => ["#title" => t("Communicate with your customer by their preferred channel (email, SMS, social, instant messaging, phone, etc.)")],
      'crm_marketing_7' => ["#title" => t("Make sure your customer data is up to date")],
      'crm_marketing_8' => ["#title" => t("Have a CRM software capable of automation")],
      'crm_marketing_9' => ["#title" => t("Customize your communications per customer")],
      'crm_marketing_10' => ["#title" => t("Segment your customers and prospects")],
      'crm_marketing_11' => ["#title" => t("Respect user's data privacy")],
      'crm_marketing_12' => ["#title" => t("Keep track of service and support records")],
      'crm_marketing_13' => ["#title" => t("Track customer reviews and satisfaction surveys")],
      'crm_marketing_14' => ["#title" => t("Make sure shipping or deliver dates are on time")],
      'crm_marketing_15' => ["#title" => t("Identify your brand influencers and advocates")],
      'crm_marketing_16' => ["#title" => t("Have a loyalty program")],
      'crm_marketing_17' => ["#title" => t("Promote and monitor your brand's word of mouth")],
      'crm_marketing_18' => ["#title" => t("Give autonomy to your employees to resolve customers issues on the spot")],

    ];

    // Ecommerce Marketing.
    $sections['ecommerce_marketing'] = [
      '#title' => t('Ecommerce Marketing'),
      '#description' => '<h2>' . t('E-commerce marketing is the practice of using promotional strategies to drive traffic towards your online store, turn that traffic into paying customers, and retain those customers after the sale.') . '</h2>',


      'ecommerce_marketing_1' => ["#title" => t("Make sure your product pages have all the details necessary to make an informed purchase")],
      'ecommerce_marketing_2' => ["#title" => t("Have a mobile commerce optimized experience (app or site)")],
      'ecommerce_marketing_3' => ["#title" => t("Have the \"Add to Wishlist\" or \"Save for later\" options")],
      'ecommerce_marketing_4' => ["#title" => t("Track, measure, and optimize your campaigns")],
      'ecommerce_marketing_5' => ["#title" => t("Include Free Shipping")],
      'ecommerce_marketing_6' => ["#title" => t("Implement a Loyalty Program")],
      'ecommerce_marketing_7' => ["#title" => t("Implement an Affiliate Program")],
      'ecommerce_marketing_8' => ["#title" => t("Reach customers in their native language")],
      'ecommerce_marketing_9' => ["#title" => t("Have a system to collect customer reviews")],
      'ecommerce_marketing_10' => ["#title" => t("Add a Live Chat")],
      'ecommerce_marketing_11' => ["#title" => t("Upsell/Cross-sell products")],
      'ecommerce_marketing_12' => ["#title" => t("Have an Abandoned Cart recovery tool")],
      'ecommerce_marketing_13' => ["#title" => t("Remind customers of their wishlist(s)")],
      'ecommerce_marketing_14' => ["#title" => t("Share User-Generated Content on the product pages")],
      'ecommerce_marketing_15' => ["#title" => t("Use high-quality product images")],
      'ecommerce_marketing_16' => ["#title" => t("Have a product image zoom feature")],
      'ecommerce_marketing_17' => ["#title" => t("Add a newsletter subscription form in the checkout")],
      'ecommerce_marketing_18' => ["#title" => t("Add video to your product pages")],
      'ecommerce_marketing_19' => ["#title" => t("Ask for customer reviews")],
      'ecommerce_marketing_20' => ["#title" => t("Add a subscription base option to your offer")],
      'ecommerce_marketing_21' => ["#title" => t("Share your product feeds in other marketplaces and shopping comparison websites")],
      'ecommerce_marketing_22' => ["#title" => t("Take advantage of promotions and coupons")],
      'ecommerce_marketing_23' => ["#title" => t("Implement structured data Reviews and Rating Schema")],
      'ecommerce_marketing_24' => ["#title" => t("Create a Google Shopping campaign")],
      'ecommerce_marketing_25' => ["#title" => t("Have an Email Marketing strategy just for e-commerce")],
      'ecommerce_marketing_26' => ["#title" => t("Implement order status notifications by SMS")],
      'ecommerce_marketing_27' => ["#title" => t("Integrate your e-commerce store with social media (Social e-commerce)")],
      'ecommerce_marketing_28' => ["#title" => t("Implement an indication of low in stock items")],
      'ecommerce_marketing_29' => ["#title" => t("Implement an out-of-stock notifications system")],
      'ecommerce_marketing_30' => ["#title" => t("Implement a free shipping threshold reminder: \"You're only X away from free shipping!\"")],
      'ecommerce_marketing_31' => ["#title" => t("Implement out-of-stock alternative recommendations")],
      'ecommerce_marketing_32' => ["#title" => t("Add product suggestions to your empty cart pages")],
      'ecommerce_marketing_33' => ["#title" => t("Add customer testimonials to your email marketing")],
      'ecommerce_marketing_34' => ["#title" => t("Implement Dynamic Remarketing Ads for e-commerce")],
      'ecommerce_marketing_35' => ["#title" => t("Track, measure, and optimize your campaigns")],
    ];

    // Measuring & Analyzing.
    $sections['measuring_analyzing'] = [
      '#title' => t('Measuring & Analyzing'),
      '#description' => '<h2>' . t('Web Analytics is all about monitoring and reporting on user data and behavior, and the marketing campaign performance over time. ') . '</h2>',

      'measuring_analyzing_1' => ["#title" => t("Set up a Web Analytics solution")],
      'measuring_analyzing_2' => ["#title" => t("Make sure all your pages have the analytics tracking tags")],
      'measuring_analyzing_3' => ["#title" => t("Configure Conversions Reports")],
      'measuring_analyzing_4' => ["#title" => t("Link your Web Analytics solution to other marketing platforms (Google Ads, Google Search Console, etc.)")],
      'measuring_analyzing_5' => ["#title" => t("Define the \"Conversion funnel\"")],
      'measuring_analyzing_6' => ["#title" => t("Enable Demographic and Interest Reports")],
      'measuring_analyzing_7' => ["#title" => t("Enable Site Search tracking")],
      'measuring_analyzing_8' => ["#title" => t("Add a value to each goal or conversion")],
      'measuring_analyzing_9' => ["#title" => t("Monitor your top landing pages")],
      'measuring_analyzing_10' => ["#title" => t("Note the dates of major events, like mentions in the media, start of marketing campaigns, etc.")],
      'measuring_analyzing_11' => ["#title" => t("Always add UTM parameters to your campaigns")],
      'measuring_analyzing_12' => ["#title" => t("Avoid sending Personally Identifiable Information (PII)")],
      'measuring_analyzing_13' => ["#title" => t("Implement E-commerce specific tracking and reporting")],
      'measuring_analyzing_14' => ["#title" => t("Create personalized reports with your defined KPIs")],
      'measuring_analyzing_15' => ["#title" => t("Make sure bots are being filtered")],
      'measuring_analyzing_16' => ["#title" => t("Track 404 pages")],
      'measuring_analyzing_17' => ["#title" => t("Check that Payment gateway referrals are excluded")],
      'measuring_analyzing_18' => ["#title" => t("Check when reports have data sampling applied")],
      'measuring_analyzing_19' => ["#title" => t("Monitor and analyze your Acquisition Reports")],
    ];

    return $sections;
  }

  /**
   * {@inheritdoc}
   */
  public function getSectionTitles(array $sections) {
    $result = [];
    foreach ($this->getAvailableSections() as $sectionKey => $sectionTitle) {
      if (in_array($sectionKey, $sections)) {
        $result[] = $sectionTitle;
      }
    }
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function clearItems(array $sections) {
    $checklistConfig = $this->config->getEditable('checklistapi.progress.' . DigitalMarketingChecklistInterface::CHECKLIST_ID);
    $savedProgress = $checklistConfig->get(ChecklistapiChecklist::PROGRESS_CONFIG_KEY);
    $deletedItems = [];
    $amountItemsDeleted = 0;
    if (isset($savedProgress['#items'])) {
      foreach ($this->getAvailableSectionsItems() as $sectionKey => $sectionItems) {
        if (in_array($sectionKey, $sections) && $sections[$sectionKey] === 0) {
          foreach ($sectionItems as $itemKey => $itemValue) {
            if (array_key_exists($itemKey, $savedProgress['#items'])) {
              $deletedItems[] = $itemValue['#title'];
              unset($savedProgress['#items'][$itemKey]);
              ++$amountItemsDeleted;
            }
          }
        }
      }
      $savedProgress['#completed_items'] -= $amountItemsDeleted;
      $checklistConfig->set(ChecklistapiChecklist::PROGRESS_CONFIG_KEY, $savedProgress);
      $checklistConfig->save();
    }
    return $deletedItems;
  }

  /**
   * {@inheritdoc}
   */
  public function isModuleInstalled($module) {
    return $this->moduleHandler->moduleExists($module);
  }

  /**
   * {@inheritdoc}
   */
  public function isSiteMultilingual() {
    // @todo dependency injection
    /** @var \Drupal\Core\Language\LanguageManagerInterface $languageManager */
    $languageManager = \Drupal::service('language_manager');
    return $languageManager->isMultilingual();
  }

  /**
   * {@inheritdoc}
   */
  public function getProjectLink($project) {
    $uri = 'https://drupal.org/project/' . $project;
    $projectName = str_replace('_', ' ', $project);
    $projectName = ucwords($projectName);
    $url = Url::fromUri($uri);
    $link = Link::fromTextAndUrl($projectName, $url);
    $link = $link->toRenderable();
    return $this->renderer->renderRoot($link);
  }

  /**
   * {@inheritdoc}
   */
  public function getProjectStatusLink($project, $should_install = TRUE) {
    // @todo improve UI, with should install hint.
    $status = t('Is *not* installed');
    // @todo check if the project is a module, a theme or a distro.
    if ($this->isModuleInstalled($project)) {
      $status = t('Is installed');
    }
    $build = [
      '#theme' => 'project_status_link',
      '#link' => $this->getProjectLink($project),
      '#status' => $status,
    ];
    return $this->renderer->renderRoot($build);
  }

  /**
   * {@inheritdoc}
   */
  public function getProjectsListStatusLink(array $projects, $should_install = TRUE) {
    $items = [];
    foreach ($projects as $project) {
      $items[] = $this->getProjectStatusLink($project, $should_install);
    }
    $build['status-link-list'] = [
      '#theme' => 'item_list',
      '#items' => $items,
      '#type' => 'ul',
    ];
    return $this->renderer->renderRoot($build);
  }

  /**
   * {@inheritdoc}
   */
  public function getAntiSpamStatusLink() {
    $projects = ['honeypot', 'captcha', 'recaptcha'];
    return $this->getProjectsListStatusLink($projects);
  }

  /**
   * {@inheritdoc}
   */
  public function getDevelopmentModulesStatusLink() {
    $projects = ['devel', 'coder'];
    return $this->getProjectsListStatusLink($projects, FALSE);
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableUpdates($type = 'security') {
    $build = [];
    if ($this->isModuleInstalled('update')) {
      $available = update_get_available(TRUE);
      $this->moduleHandler->loadInclude('update', 'compare.inc');
      $build['#data'] = update_calculate_project_data($available);
    }
    return $this->renderer->renderRoot($build);
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableSecurityUpdatesAmount() {
    $result = 0;
    // @todo implement
    // $updates = $this->getAvailableUpdates('security');
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function getSecurityUpdatesChecklistArray() {
    if ($this->isModuleInstalled('update')) {
      return [
        '#title' => t('Check available updates'),
        'path' => [
          '#text' => t('Available updates'),
          '#url' => Url::fromRoute('update.status'),
        ],
      ];
    }
    else {
      return [
        '#title' => t('Check available updates'),
        '#description' => t('Update notifications are not enabled. It is <strong>highly recommended</strong> that you enable the Update Manager module from the <a href=":module">module administration page</a> in order to stay up-to-date on new releases. For more information, <a href=":update">Update status handbook page</a>.', [
          ':update' => 'https://www.drupal.org/documentation/modules/update',
          ':module' => Url::fromRoute('system.modules_list')->toString(),
        ]),
        'path' => $this->getModulesPageTextUrl(),
      ];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getModulesPageLink() {
    $route = 'system.modules_list';
    $link = Link::createFromRoute(t('Modules'), $route);
    $link = $link->toRenderable();
    return $this->renderer->renderRoot($link);
  }

  /**
   * {@inheritdoc}
   */
  public function getModulesPageTextUrl() {
    return [
      '#text' => t('Modules'),
      '#url' => Url::fromRoute('system.modules_list'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getModulesUninstallPageTextUrl() {
    return [
      '#text' => t('Uninstall modules'),
      '#url' => Url::fromRoute('system.modules_uninstall'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldsFromType($type) {
    $fields = [];
    try {
      $fields = $this->entityTypeManager->getStorage('field_storage_config')
        ->loadByProperties(['type' => 'email']);
    } catch (\Exception $exception) {
      $this->messenger->addError($exception->getMessage());
    }
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmailObfuscationDescription() {
    $output = '';
    $output .= t('Are the email addresses protected against bots harvesting? Email addresses can be present in fields, WYSIWYG, Twig.');
    // @todo get email fields then report usage and review formatter.
    // $fields = $this->getFieldsFromType('email');
    return $output;
  }

}
