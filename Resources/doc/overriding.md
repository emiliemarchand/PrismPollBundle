## Overriding the bundle

### Create your own bundle and define PrismPollBundle as the parent

Example: `src/Application/Prism/PollBundle`

``` php
<?php

namespace Application\Prism\PollBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApplicationPrismPollBundle extends Bundle
{
    public function getParent()
    {
        return 'PrismPollBundle';
    }
}
```

## Overriding entities

PrismPollBundle already comes with concrete entity classes. You do not need to create them.
However, if you need to add fields or associations, you'll need to create your own entity classes and extend the mapped superclasses of the bundle:

### Define your mapping information

Example:

``` yaml
# src/Application/Prism/PollBundle/config/doctrine/Poll.orm.yml
Application\Prism\PollBundle\Entity\Poll:
  type: entity
  table: AppPrismPoll
  repositoryClass: Application\Prism\PollBundle\Entity\PollRepository
  fields:
    # Custom fields:
    author:
      type: string
      length: 255
      nullable: false
```

``` yaml
# src/Application/Prism/PollBundle/config/doctrine/Opinion.orm.yml
Application\Prism\PollBundle\Entity\Opinion:
  type: entity
  table: AppPrismPollOpinion
  repositoryClass: Application\Prism\PollBundle\Entity\OpinionRepository
```

You only need to specify your custom fields. Do NOT repeat the fields and associations that comes with the original bundle.

### Edit the default configuration to use your entities instead of the default ones

``` yaml
# app/config/config.yml
prism_poll:
    entity:
        poll:     Application\Prism\PollBundle\Entity\Poll
        opinion:  Application\Prism\PollBundle\Entity\Opinion
```

### Generate your entities from the mapping information

``` bash
$ app/console doctrine:generate:entities ApplicationPrismPollBundle
```

### Edit the generated entities to make them extend the mapped superclasses

``` php
<?php

namespace Application\Prism\PollBundle\Entity;

use Prism\PollBundle\Entity\BasePoll;

class Poll extends BasePoll
{
```

``` php
<?php

namespace Application\Prism\PollBundle\Entity;

use Prism\PollBundle\Entity\BaseOpinion;

class Opinion extends BaseOpinion
{
```

### Remove repeated associations

Ok now this is the ugly part.
Right now Doctrine has [limitations to defining associations on mapped superclasses](http://docs.doctrine-project.org/projects/doctrine-orm/en/2.1/reference/inheritance-mapping.html), so you'll need to do some clean-up by hand.
(I haven't found out yet how to do this another way)

If you look at your generated Poll class, you will see that the default fields aren't repeated in your class (id, name etc.), which is perfect, yay!
However, the oneToMany association between Poll and Opinion has been defined again. Since it is already in the base class, you need to remove it from your class:

Remove:

``` php
    /**
     * @var Prism\PollBundle\Entity\Opinion
     */
    private $opinions;

    public function __construct()
    {
        $this->opinions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add opinions
     *
     * @param Prism\PollBundle\Entity\Opinion $opinions
     */
    public function addOpinion(\Prism\PollBundle\Entity\Opinion $opinions)
    {
        $this->opinions[] = $opinions;
    }

    /**
     * Get opinions
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getOpinions()
    {
        return $this->opinions;
    }
```

Your class should now look like this:

``` php
<?php

namespace Application\Prism\PollBundle\Entity;

use Prism\PollBundle\Entity\BasePoll;

class Poll extends BasePoll
{
    /**
     * @var string $author
     */
    private $author;

    /**
     * Set author
     *
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
```

### Edit the generated entity repositories to make them extend the base ones

``` php
<?php

namespace Application\Prism\PollBundle\Entity;

use Prism\PollBundle\Entity\PollRepository as BasePollRepository;

class PollRepository extends BasePollRepository
{
}
```

``` php
<?php

namespace Application\Prism\PollBundle\Entity;

use Prism\PollBundle\Entity\PollRepository as BaseOpinionRepository;

class OpinionRepository extends BaseOpinionRepository
{
}
```


### Generate your tables

``` bash
$ app/console doctrine:schema:update --force
```

As you can see you now have 4 tables: AppPrismPoll, AppPrismPollOpinion and PrimPoll, PrismPollOpinion. The default tables are still generated but they will not be used.
You can safely delete them (but they will be generated each time you run the command). This is another ugly problem that I haven't found a way to overcome yet.


## Overriding forms

### Create your form types and make them extend the base ones

Example:

``` php
<?php

namespace Application\Prism\PollBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Prism\PollBundle\Form\PollType as BasePollType;

class PollType extends BasePollType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('author');
    }
}
```

``` php
<?php

namespace Application\Prism\PollBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Prism\PollBundle\Form\OpinionType as BaseOpinionType;

class OpinionType extends BaseOpinionType
{
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Application\Prism\PollBundle\Entity\Opinion',
        );
    }
}
```

### Edit the default configuration to use your forms instead of the default ones

``` yaml
# app/config/config.yml
prism_poll:
    form:
        poll:     Application\Prism\PollBundle\Form\PollType
        opinion:  Application\Prism\PollBundle\Form\OpinionType
```